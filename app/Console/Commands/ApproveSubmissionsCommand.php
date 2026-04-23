<?php

namespace App\Console\Commands;

use App\Models\Submission;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class ApproveSubmissionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'submissions:approve-all
        {--prodi_id= : Filter by Program Studi ID}
        {--kriteria_id= : Filter by Kriteria ID}
        {--validator_id= : Validator ID (default: 1)}
        {--komentar= : Optional comment for all approvals}
        {--force : Skip confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bulk approve all pending submissions (for testing purposes)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get filter parameters
        $prodi_id = $this->option('prodi_id');
        $kriteria_id = $this->option('kriteria_id');
        $validator_id = $this->option('validator_id') ?? 1;
        $komentar = $this->option('komentar') ?? 'Disetujui via bulk approve command';
        $force = $this->option('force');

        // Build query for pending submissions
        $query = Submission::where('status', 'submitted');

        if ($prodi_id) {
            $query->where('prodi_id', $prodi_id);
        }

        if ($kriteria_id) {
            $query->where('kriteria_id', $kriteria_id);
        }

        $submissions = $query->get();

        if ($submissions->isEmpty()) {
            $this->info('❌ Tidak ada submission dengan status pending.');
            return Command::SUCCESS;
        }

        // Display summary
        $this->line("\n📋 SUMMARY BULK APPROVE");
        $this->line(str_repeat('=', 50));
        $this->info("Total submissions to approve: {$submissions->count()}");
        if ($prodi_id) {
            $this->info("Filter by Prodi ID: {$prodi_id}");
        }
        if ($kriteria_id) {
            $this->info("Filter by Kriteria ID: {$kriteria_id}");
        }
        $this->info("Validator ID: {$validator_id}");
        $this->info("Comment: {$komentar}");
        $this->line(str_repeat('=', 50) . "\n");

        // Confirm before proceeding
        if (!$force && !$this->confirm('Lanjutkan dengan approve semua?')) {
            $this->warn('❌ Dibatalkan');
            return Command::SUCCESS;
        }

        // Verify validator exists
        $validator = User::find($validator_id);
        if (!$validator) {
            $this->error("❌ Validator dengan ID {$validator_id} tidak ditemukan");
            return Command::FAILURE;
        }

        // Process each submission
        $approved = 0;
        $failed = 0;

        $this->output->progressStart($submissions->count());

        foreach ($submissions as $submission) {
            try {
                // Create/update validasi record
                $submission->validasi()->updateOrCreate(
                    ['submission_id' => $submission->submission_id],
                    [
                        'validator_id' => $validator_id,
                        'status' => 'disetujui',
                        'komentar' => $komentar,
                        'validated_at' => now(),
                    ]
                );

                // Update submission status
                $submission->update([
                    'status' => 'diterima',
                    'updated_at' => now()
                ]);

                // Log the validation action (pass validator_id for audit log)
                AuditLogService::logApproval($submission, $validator_id);

                $approved++;
            } catch (\Exception $e) {
                $failed++;
                $this->error("Failed to approve submission {$submission->submission_id}: {$e->getMessage()}");
            }

            $this->output->progressAdvance();
        }

        $this->output->progressFinish();

        // Display results
        $this->line("\n✅ HASIL APPROVAL");
        $this->line(str_repeat('=', 50));
        $this->info("✓ Berhasil: {$approved}");
        if ($failed > 0) {
            $this->warn("✗ Gagal: {$failed}");
        }
        $this->line(str_repeat('=', 50) . "\n");

        return Command::SUCCESS;
    }
}
