<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $table = 'submission';
    protected $primaryKey = 'submission_id';
    public $timestamps = false;
    protected $fillable = ['prodi_id', 'kriteria_id', 'user_id', 'status', 'skor', 'submitted_at', 'updated_at'];

    // Valid status values per PRD
    public const STATUS_DRAFT = 'draft';
    public const STATUS_SUBMITTED = 'submitted';
    public const STATUS_APPROVED = 'diterima';
    public const STATUS_REVISION = 'revisi';
    public const STATUS_REJECTED = 'ditolak';

    public function prodi()
    {
        return $this->belongsTo(ProgramStudi::class, 'prodi_id');
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items()
    {
        return $this->hasMany(SubmissionItem::class, 'submission_id');
    }

    public function validasi()
    {
        return $this->hasOne(Validasi::class, 'submission_id');
    }

    /**
     * State machine: Check if transition from current status to new status is valid
     * 
     * Valid transitions per PRD:
     * - draft → submitted: allowed
     * - submitted → diterima (approved): only by validator
     * - submitted → revisi: only by validator, komentar required
     * - submitted → ditolak: only by validator, komentar required
     * - revisi → draft: automatic reset
     * - diterima (approved): LOCKED, no changes without admin intervention
     *
     * @param string $newStatus
     * @param string $userRole (optional, for validator checks)
     * @return array ['valid' => bool, 'message' => string]
     */
    public function canTransitionTo(string $newStatus, string $userRole = null): array
    {
        $currentStatus = $this->status;

        // Same status = no transition needed
        if ($currentStatus === $newStatus) {
            return ['valid' => true, 'message' => 'Status tidak berubah'];
        }

        // Approved status is locked
        if ($currentStatus === self::STATUS_APPROVED) {
            return ['valid' => false, 'message' => 'Submission yang sudah disetujui tidak dapat diubah. Hubungi admin untuk reset.'];
        }

        // Define valid transitions
        $validTransitions = [
            self::STATUS_DRAFT => [self::STATUS_SUBMITTED, self::STATUS_DRAFT],  // draft → submitted or draft (save)
            self::STATUS_SUBMITTED => [self::STATUS_APPROVED, self::STATUS_REVISION, self::STATUS_REJECTED],  // submitted → validator actions
            self::STATUS_REVISION => [self::STATUS_DRAFT],  // revisi → draft (reset for re-submission)
            self::STATUS_REJECTED => [self::STATUS_DRAFT],  // ditolak → draft (reset for re-submission)
        ];

        // Check if transition is in valid list
        if (!isset($validTransitions[$currentStatus]) || !in_array($newStatus, $validTransitions[$currentStatus])) {
            return ['valid' => false, 'message' => "Transisi dari {$currentStatus} ke {$newStatus} tidak diizinkan"];
        }

        // Validator-only checks
        if ($newStatus !== self::STATUS_DRAFT) {
            if ($userRole !== 'validator' && $userRole !== 'admin') {
                return ['valid' => false, 'message' => 'Hanya validator yang dapat mengubah status submission'];
            }
        }

        return ['valid' => true, 'message' => 'Transisi diizinkan'];
    }

    /**
     * Check if submission can be edited by dosen
     * Cannot edit if status = 'diterima' (approved)
     *
     * @return bool
     */
    public function canEdit(): bool
    {
        return $this->status !== self::STATUS_APPROVED;
    }
}
