<?php

namespace App\Console\Commands;

use App\Models\Submission;
use App\Services\AuditLogService;
use Illuminate\Console\Command;

class SubmitDraftsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'submissions:submit-drafts
        {--prodi_id= : Filter by Program Studi ID}
        {--kriteria_id= : Filter by Kriteria ID}
        {--force : Skip confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bulk submit all draft submissions to submitted status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get filter parameters
        $prodi_id = $this->option('prodi_id');
        $kriteria_id = $this->option('kriteria_id');
        $force = $this->option('force');

        // Build query for draft submissions
        $query = Submission::where('status', 'draft');

        if ($prodi_id) {
            $query->where('prodi_id', $prodi_id);
        }

        if ($kriteria_id) {
            $query->where('kriteria_id', $kriteria_id);
        }

        $submissions = $query->get();

        if ($submissions->isEmpty()) {
            $this->info('❌ Tidak ada submission dengan status draft.');
            return Command::SUCCESS;
        }

        // Display summary
        $this->line("\n📋 SUMMARY BULK SUBMIT");
        $this->line(str_repeat('=', 50));
        $this->info("Total submissions to submit: {$submissions->count()}");
        if ($prodi_id) {
            $this->info("Filter by Prodi ID: {$prodi_id}");
        }
        if ($kriteria_id) {
            $this->info("Filter by Kriteria ID: {$kriteria_id}");
        }
        $this->line(str_repeat('=', 50) . "\n");

        // Confirm before proceeding
        if (!$force && !$this->confirm('Lanjutkan dengan submit semua draft?')) {
            $this->warn('❌ Dibatalkan');
            return Command::SUCCESS;
        }

        // Process each submission
        $submitted = 0;
        $failed = 0;

        $this->output->progressStart($submissions->count());

        foreach ($submissions as $submission) {
            try {
                // Update submission status
                $submission->update([
                    'status' => 'submitted',
                    'updated_at' => now()
                ]);

                // Log the submission action
                AuditLogService::log($submission, 'submitted', [
                    'status' => ['old' => 'draft', 'new' => 'submitted'],
                ], $submission->user_id);

                $submitted++;
            } catch (\Exception $e) {
                $failed++;
                $this->error("Failed to submit submission {$submission->submission_id}: {$e->getMessage()}");
            }

            $this->output->progressAdvance();
        }

        $this->output->progressFinish();

        // Display results
        $this->line("\n✅ HASIL SUBMIT");
        $this->line(str_repeat('=', 50));
        $this->info("✓ Berhasil: {$submitted}");
        if ($failed > 0) {
            $this->warn("✗ Gagal: {$failed}");
        }
        $this->line(str_repeat('=', 50) . "\n");

        return Command::SUCCESS;
    }
}
