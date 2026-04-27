<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Validasi extends Model
{
    protected $table = 'validasi';
    protected $primaryKey = 'validasi_id';
    public $timestamps = false;
    protected $fillable = ['submission_id', 'validator_id', 'status', 'komentar', 'validated_at'];

    public function submission()
    {
        return $this->belongsTo(Submission::class, 'submission_id');
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validator_id');
    }

    // Backwards-compatible alias used in some controllers/views
    public function user()
    {
        return $this->validator();
    }
}
