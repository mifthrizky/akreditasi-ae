<?php

namespace App\Services;

use App\Models\Submission;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditLogService
{
    /**
     * Log a submission action/change
     * 
     * @param Submission $submission
     * @param string $action ('created', 'updated', 'submitted', 'approved', 'rejected', 'revision')
     * @param array $changes (optional) ['field_name' => ['old' => value, 'new' => value]]
     * @param int|null $userId (optional) User ID for audit log. If null, uses Auth::id()
     */
    public static function log(Submission $submission, string $action, array $changes = [], ?int $userId = null): AuditLog
    {
        $changedFields = array_keys($changes);
        $oldValues = [];
        $newValues = [];

        foreach ($changes as $field => $change) {
            $oldValues[$field] = $change['old'] ?? null;
            $newValues[$field] = $change['new'] ?? null;
        }

        // Use provided userId or fallback to Auth::id()
        $userId = $userId ?? Auth::id();

        return AuditLog::create([
            'submission_id' => $submission->submission_id,
            'user_id' => $userId,
            'action' => $action,
            'changed_fields' => !empty($changedFields) ? $changedFields : null,
            'old_values' => !empty($oldValues) ? $oldValues : null,
            'new_values' => !empty($newValues) ? $newValues : null,
        ]);
    }

    /**
     * Log submission creation
     */
    public static function logCreation(Submission $submission): AuditLog
    {
        return self::log($submission, 'created');
    }

    /**
     * Log submission item updates (field changes)
     */
    public static function logUpdate(Submission $submission, array $changes): AuditLog
    {
        return self::log($submission, 'updated', $changes);
    }

    /**
     * Log submission submission (draft → submitted)
     */
    public static function logSubmit(Submission $submission, float $score): AuditLog
    {
        return self::log($submission, 'submitted', [
            'status' => ['old' => 'draft', 'new' => 'submitted'],
            'skor' => ['old' => null, 'new' => $score],
        ]);
    }

    /**
     * Log validator approval (submitted → diterima)
     */
    public static function logApproval(Submission $submission, ?int $userId = null): AuditLog
    {
        return self::log($submission, 'approved', [
            'status' => ['old' => 'submitted', 'new' => 'diterima'],
        ], $userId);
    }

    /**
     * Log validator rejection (submitted → ditolak)
     */
    public static function logRejection(Submission $submission, ?int $userId = null): AuditLog
    {
        return self::log($submission, 'rejected', [
            'status' => ['old' => 'submitted', 'new' => 'ditolak'],
        ], $userId);
    }

    /**
     * Log validator revision request (submitted → revisi)
     */
    public static function logRevision(Submission $submission, ?int $userId = null): AuditLog
    {
        return self::log($submission, 'revision', [
            'status' => ['old' => 'submitted', 'new' => 'revisi'],
        ], $userId);
    }

    /**
     * Get audit log history for a submission
     */
    public static function getHistory(Submission $submission, int $limit = null)
    {
        $query = AuditLog::where('submission_id', $submission->submission_id)
            ->with('user')
            ->orderBy('created_at', 'desc');

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }
    
    /**
     * Log prodi data deletion (bulk cleanup)
     */
    public static function logProdiDataDeletion(
        \App\Models\ProgramStudi $prodi, 
        array $summary, 
        string $backupPath
    ): AuditLog
    {
        return AuditLog::create([
            'submission_id' => null,
            'user_id' => auth()->id(),
            'action' => 'prodi_data_deleted',
            'changed_fields' => ['prodi_id', 'prodi_nama', 'prodi_kode'],
            'old_values' => [
                'prodi_id' => $prodi->prodi_id,
                'prodi_nama' => $prodi->nama,
                'prodi_kode' => $prodi->kode,
                'jurusan' => $prodi->jurusan,
            ],
            'new_values' => [
                'deleted_submissions' => $summary['submission_count'],
                'deleted_items' => $summary['item_count'],
                'deleted_dokumen' => $summary['dokumen_count'],
                'deleted_laporan' => $summary['laporan_count'],
                'total_records' => $summary['total_records'],
                'disk_freed_mb' => $summary['total_size_mb'],
                'backup_path' => $backupPath,
                'deleted_at' => now()->toIso8601String(),
            ],
        ]);
    }
}
