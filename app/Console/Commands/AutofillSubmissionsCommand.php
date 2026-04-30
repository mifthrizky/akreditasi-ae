<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Submission;
use App\Models\SubmissionItem;
use App\Models\User;
use App\Models\Kriteria;
use App\Services\SkorService;
use App\Services\AuditLogService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\File as HttpFile;

class AutofillSubmissionsCommand extends Command
{
    protected $signature = 'submissions:autofill
        {--prodi_id= : Filter by prodi_id}
        {--kriteria_id= : Filter by kriteria_id}
        {--force : Force submit even if score < 50}
        {--document= : Specific dummy document filename to use (from public/dummy)}
        {--dry-run : Do not persist changes}';

    protected $description = 'Auto-create, fill submission items using default values, then attempt to submit';

    public function handle()
    {
        $prodiId = $this->option('prodi_id');
        $kriteriaId = $this->option('kriteria_id');
        $force = $this->option('force');
        $dryRun = $this->option('dry-run');
        $documentOpt = $this->option('document');

        // Find available dummy files
        $dummyDir = base_path('public/dummy');
        $dummyFiles = [];
        if (is_dir($dummyDir)) {
            $all = glob($dummyDir . DIRECTORY_SEPARATOR . '*');
            foreach ($all as $f) {
                if (is_file($f)) {
                    $dummyFiles[] = $f;
                }
            }
        }

        if ($documentOpt) {
            $found = null;
            foreach ($dummyFiles as $f) {
                if (basename($f) === $documentOpt) {
                    $found = $f;
                    break;
                }
            }
            if ($found) {
                $dummyFiles = [$found];
            } else {
                $this->warn("Requested document '{$documentOpt}' not found in public/dummy. Using defaults.");
            }
        }

        // Auto-create missing submissions if prodi_id provided
        if ($prodiId) {
            $this->line("Auto-creating missing submissions for users assigned to prodi_id={$prodiId}...");
            $this->createMissingSubmissions($prodiId, $kriteriaId, $dryRun);
        }

        // Query submissions in draft or revisi
        $query = Submission::whereIn('status', ['draft', 'revisi']);
        if ($prodiId) $query->where('prodi_id', $prodiId);
        if ($kriteriaId) $query->where('kriteria_id', $kriteriaId);

        $submissions = $query->get();
        $total = $submissions->count();
        $submitted = 0;
        $skipped = 0;

        $this->info("Found {$total} submissions to process");

        foreach ($submissions as $submission) {
            $this->line("Processing submission #{$submission->submission_id} (status: {$submission->status})");

            DB::beginTransaction();
            try {
                $templateItems = $submission->kriteria->templateItems()->get();

                foreach ($templateItems as $template) {
                    $inputName = 'template_' . $template->template_id;

                    $item = SubmissionItem::firstOrCreate(
                        ['submission_id' => $submission->submission_id, 'template_item_id' => $template->template_id],
                        []
                    );

                    // Only fill if empty to avoid clobbering existing answers
                    if ($template->tipe === 'checklist') {
                        if (empty($item->nilai_checklist)) {
                            if (!$dryRun) $item->nilai_checklist = true;
                        }
                    } elseif ($template->tipe === 'upload') {
                        if (empty($item->nilai_teks)) {
                            if (!empty($dummyFiles)) {
                                $src = $dummyFiles[0];
                                $orig = basename($src);
                                $destName = $submission->submission_id . '_t' . $template->template_id . '_' . Str::random(6) . '_' . $orig;
                                if (!$dryRun) {
                                    $storedPath = Storage::disk('public')->putFileAs('submissions', new HttpFile($src), $destName);
                                    $item->nilai_teks = $storedPath;
                                }
                                if ($this->output->isVerbose()) $this->line("  - upload set to {$destName}");
                            } else {
                                if ($this->output->isVerbose()) $this->line("  - no dummy files available to satisfy upload template {$template->template_id}");
                            }
                        }
                    } elseif ($template->tipe === 'numerik') {
                        if (is_null($item->nilai_numerik)) {
                            $val = $template->nilai_min_numerik ?? 1;
                            if (!$dryRun) $item->nilai_numerik = $val;
                        }
                    } elseif ($template->tipe === 'narasi') {
                        if (empty($item->nilai_teks)) {
                            if (!$dryRun) $item->nilai_teks = 'diisi';
                        }
                    }

                    if (!$dryRun) $item->save();
                }

                // compute score
                $skorService = app(SkorService::class);
                $score = $skorService->calculate($submission);

                $canSubmit = $score >= 50 || $force;

                if ($canSubmit) {
                    if (!$dryRun) {
                        $oldSkor = $submission->skor ?? null;
                        $submission->status = 'submitted';
                        $submission->submitted_at = now();
                        $submission->skor = $score;
                        $submission->save();
                        // Use submission owner for audit log
                        $auditUserId = $submission->user_id ?? 1;
                        AuditLogService::log($submission, 'submitted', [
                            'status' => ['old' => 'draft', 'new' => 'submitted'],
                            'skor' => ['old' => $oldSkor, 'new' => $score],
                        ], $auditUserId);
                    }
                    $submitted++;
                    $this->info(" -> Submitted (score: {$score}%)");
                } else {
                    $skipped++;
                    $this->warn(" -> Skipped (score: {$score}% < 50)");
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("Error processing submission #{$submission->submission_id}: " . $e->getMessage());
                continue;
            }
        }

        $this->info("Done. Processed: {$total}. Submitted: {$submitted}. Skipped: {$skipped}.");

        return 0;
    }

    /**
     * Auto-create missing submissions for all users assigned to a prodi
     */
    private function createMissingSubmissions(?int $prodiId, ?int $kriteriaId, bool $dryRun): void
    {
        if (!$prodiId) return;

        // Get all users assigned to this prodi
        $users = User::whereHas('prodis', function ($q) use ($prodiId) {
            $q->where('program_studi.prodi_id', $prodiId);
        })->get();

        if ($users->isEmpty()) {
            $this->warn("No users assigned to prodi_id={$prodiId}");
            return;
        }

        // Get all level-2 kriteria
        $kriteriaQuery = Kriteria::where('level', 2);
        if ($kriteriaId) {
            $kriteriaQuery->where('kriteria_id', $kriteriaId);
        }
        $kriterias = $kriteriaQuery->get();

        $created = 0;
        foreach ($users as $user) {
            foreach ($kriterias as $kriteria) {
                $exists = Submission::where('user_id', $user->user_id)
                    ->where('prodi_id', $prodiId)
                    ->where('kriteria_id', $kriteria->kriteria_id)
                    ->exists();

                if (!$exists) {
                    if (!$dryRun) {
                        Submission::create([
                            'user_id' => $user->user_id,
                            'prodi_id' => $prodiId,
                            'kriteria_id' => $kriteria->kriteria_id,
                            'status' => 'draft',
                            'submitted_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                    $created++;
                }
            }
        }

        $this->info("Created {$created} missing submissions for prodi_id={$prodiId}");
    }
}
