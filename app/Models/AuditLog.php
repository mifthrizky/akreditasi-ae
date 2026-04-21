<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $table = 'audit_logs';
    protected $primaryKey = 'id';
    public $timestamps = false; // We only have created_at, no updated_at
    protected $fillable = ['submission_id', 'user_id', 'action', 'changed_fields', 'old_values', 'new_values', 'created_at'];

    // Cast JSON columns to arrays
    protected $casts = [
        'changed_fields' => 'array',
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    public function submission()
    {
        return $this->belongsTo(Submission::class, 'submission_id', 'submission_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Get human-readable action label
     */
    public function getActionLabel(): string
    {
        return match ($this->action) {
            'created' => 'Dibuat',
            'updated' => 'Diperbarui',
            'submitted' => 'Disubmit',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'revision' => 'Diminta Revisi',
            default => ucfirst($this->action),
        };
    }
}
