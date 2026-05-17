<?php

namespace App\Services;

use App\Models\ProgramStudi;
use App\Models\Submission;
use App\Models\Laporan;
use App\Models\Dokumen;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ProdiDataCleanupService
{
    /**
     * Get summary of data that will be deleted with validation
     */
    public function getDataSummary(int $prodiId): array
    {
        $prodi = ProgramStudi::findOrFail($prodiId);
        
        // Get all submissions
        $submissions = Submission::where('prodi_id', $prodiId)->get();
        $submissionCount = $submissions->count();
        
        // Count submission items
        $itemCount = 0;
        foreach ($submissions as $submission) {
            $itemCount += $submission->items()->count();
        }
        
        // Count dokumen files
        $dokumenRecords = Dokumen::whereIn('subitem_id', function($query) use ($prodiId) {
            $query->select('subitem_id')
                  ->from('submission_item')
                  ->whereIn('submission_id', function($q) use ($prodiId) {
                      $q->select('submission_id')
                        ->from('submission')
                        ->where('prodi_id', $prodiId);
                  });
        })->get();
        
        $dokumenCount = $dokumenRecords->count();
        $dokumenSize = 0;
        
        foreach ($dokumenRecords as $dok) {
            if (Storage::disk('public')->exists($dok->path_file)) {
                $dokumenSize += Storage::disk('public')->size($dok->path_file);
            }
        }
        
        // Count laporan PDFs
        $laporans = Laporan::where('prodi_id', $prodiId)->get();
        $laporanCount = $laporans->count();
        $laporanSize = 0;
        
        foreach ($laporans as $lap) {
            if (Storage::disk('public')->exists($lap->path_pdf)) {
                $laporanSize += Storage::disk('public')->size($lap->path_pdf);
            }
        }
        
        // Count validasi
        $validasiCount = DB::table('validasi')
            ->whereIn('submission_id', $submissions->pluck('submission_id'))
            ->count();
        
        // ✨ VALIDATION: Check for pending review (submitted or revisi)
        $pendingReview = $submissions->whereIn('status', ['submitted', 'revisi']);
        $hasPendingReview = $pendingReview->count() > 0;
        $pendingReviewCount = $pendingReview->count();
        
        // Get details of pending submissions
        $pendingDetails = $pendingReview->map(function($sub) {
            return [
                'kriteria_kode' => $sub->kriteria->kode ?? 'N/A',
                'kriteria_nama' => $sub->kriteria->nama ?? 'N/A',
                'status' => $sub->status,
                'submitted_at' => $sub->submitted_at,
                'days_ago' => Carbon::parse($sub->submitted_at)->diffForHumans(),
            ];
        })->values()->toArray();
        
        // ✨ WARNING: Check for recent updates (within 7 days)
        $sevenDaysAgo = Carbon::now()->subDays(7);
        $recentUpdates = $submissions->filter(function($sub) use ($sevenDaysAgo) {
            return Carbon::parse($sub->updated_at)->isAfter($sevenDaysAgo);
        });
        
        $hasRecentUpdates = $recentUpdates->count() > 0;
        $recentUpdateCount = $recentUpdates->count();
        
        // Get most recent update date
        $mostRecentUpdate = $submissions->max('updated_at');
        $recentUpdateDays = $mostRecentUpdate 
            ? Carbon::parse($mostRecentUpdate)->diffInDays(Carbon::now())
            : null;
        
        // Get details of recent updates
        $recentUpdateDetails = $recentUpdates->map(function($sub) {
            return [
                'kriteria_kode' => $sub->kriteria->kode ?? 'N/A',
                'kriteria_nama' => $sub->kriteria->nama ?? 'N/A',
                'updated_at' => $sub->updated_at,
                'days_ago' => Carbon::parse($sub->updated_at)->diffForHumans(),
                'updated_by' => $sub->user->nama ?? 'N/A',
            ];
        })->values()->toArray();
        
        // ✨ DECISION: Can delete?
        $canDelete = !$hasPendingReview;
        $blockingReason = null;
        $warnings = [];
        
        if ($hasPendingReview) {
            $blockingReason = "Masih ada {$pendingReviewCount} submission yang belum direview (status: submitted/revisi)";
        }
        
        if ($hasRecentUpdates) {
            $warnings[] = "Ada {$recentUpdateCount} submission yang baru diupdate dalam {$recentUpdateDays} hari terakhir";
        }
        
        return [
            'prodi' => $prodi,
            'submission_count' => $submissionCount,
            'item_count' => $itemCount,
            'dokumen_count' => $dokumenCount,
            'dokumen_size_bytes' => $dokumenSize,
            'dokumen_size_mb' => round($dokumenSize / 1024 / 1024, 2),
            'laporan_count' => $laporanCount,
            'laporan_size_bytes' => $laporanSize,
            'laporan_size_mb' => round($laporanSize / 1024 / 1024, 2),
            'validasi_count' => $validasiCount,
            'total_records' => $submissionCount + $itemCount + $dokumenCount + $laporanCount + $validasiCount,
            'total_size_mb' => round(($dokumenSize + $laporanSize) / 1024 / 1024, 2),
            
            // ✨ Validation results
            'has_pending_review' => $hasPendingReview,
            'pending_review_count' => $pendingReviewCount,
            'pending_details' => $pendingDetails,
            
            'has_recent_updates' => $hasRecentUpdates,
            'recent_update_count' => $recentUpdateCount,
            'recent_update_days' => $recentUpdateDays,
            'recent_update_details' => $recentUpdateDetails,
            
            'can_delete' => $canDelete,
            'blocking_reason' => $blockingReason,
            'warnings' => $warnings,
        ];
    }
    
    /**
     * Create backup before deletion
     */
    public function createBackup(int $prodiId): string
    {
        $prodi = ProgramStudi::with([
            'submissions.items',
            'submissions.validasi',
            'laporans'
        ])->findOrFail($prodiId);
        
        $backup = [
            'backup_created_at' => now()->toIso8601String(),
            'backup_created_by' => auth()->user()->nama,
            'backup_created_by_id' => auth()->id(),
            'prodi' => [
                'prodi_id' => $prodi->prodi_id,
                'kode' => $prodi->kode,
                'nama' => $prodi->nama,
                'jurusan' => $prodi->jurusan,
            ],
            'submissions' => $prodi->submissions->toArray(),
            'laporans' => $prodi->laporans->toArray(),
            'statistics' => $this->getDataSummary($prodiId),
        ];
        
        $filename = "prodi_backup_{$prodi->kode}_" . now()->format('YmdHis') . ".json";
        $path = "backups/{$filename}";
        
        Storage::disk('local')->put($path, json_encode($backup, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        
        return $path;
    }
    
    /**
     * Delete all prodi data with transaction safety
     */
    public function deleteProdiData(int $prodiId): array
    {
        try {
            DB::beginTransaction();
            
            $prodi = ProgramStudi::findOrFail($prodiId);
            $summary = $this->getDataSummary($prodiId);
            
            // ✨ VALIDATION: Check if can delete
            if (!$summary['can_delete']) {
                return [
                    'success' => false,
                    'message' => $summary['blocking_reason'],
                ];
            }
            
            // Step 1: Create backup
            $backupPath = $this->createBackup($prodiId);
            
            // Step 2: Delete physical files (dokumen)
            $deletedDokumen = $this->cleanupDokumenFiles($prodiId);
            
            // Step 3: Delete physical files (laporan PDFs)
            $deletedLaporan = $this->cleanupLaporanFiles($prodiId);
            
            // Step 4: Delete database records (cascade will handle children)
            $deletedSubmissions = Submission::where('prodi_id', $prodiId)->delete();
            $deletedLaporanRecords = Laporan::where('prodi_id', $prodiId)->delete();
            
            // Step 5: Log audit trail
            AuditLogService::logProdiDataDeletion($prodi, $summary, $backupPath);
            
            DB::commit();
            
            return [
                'success' => true,
                'message' => 'Data program studi berhasil dihapus',
                'summary' => $summary,
                'backup_path' => $backupPath,
                'deleted' => [
                    'submissions' => $deletedSubmissions,
                    'laporans' => $deletedLaporanRecords,
                    'dokumen_files' => $deletedDokumen,
                    'laporan_files' => $deletedLaporan,
                ],
            ];
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Prodi data cleanup failed', [
                'prodi_id' => $prodiId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return [
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage(),
            ];
        }
    }
    
    /**
     * Delete dokumen physical files
     */
    private function cleanupDokumenFiles(int $prodiId): int
    {
        $dokumenRecords = Dokumen::whereIn('subitem_id', function($query) use ($prodiId) {
            $query->select('subitem_id')
                  ->from('submission_item')
                  ->whereIn('submission_id', function($q) use ($prodiId) {
                      $q->select('submission_id')
                        ->from('submission')
                        ->where('prodi_id', $prodiId);
                  });
        })->get();
        
        $deletedCount = 0;
        
        foreach ($dokumenRecords as $dok) {
            if (Storage::disk('public')->exists($dok->path_file)) {
                Storage::disk('public')->delete($dok->path_file);
                $deletedCount++;
            }
        }
        
        return $deletedCount;
    }
    
    /**
     * Delete laporan PDF files
     */
    private function cleanupLaporanFiles(int $prodiId): int
    {
        $laporans = Laporan::where('prodi_id', $prodiId)->get();
        $deletedCount = 0;
        
        foreach ($laporans as $lap) {
            if (Storage::disk('public')->exists($lap->path_pdf)) {
                Storage::disk('public')->delete($lap->path_pdf);
                $deletedCount++;
            }
        }
        
        return $deletedCount;
    }
}
