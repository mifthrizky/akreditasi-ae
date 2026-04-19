<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $table = 'submission';
    protected $primaryKey = 'submission_id';
    public $timestamps = false;
    protected $fillable = ['prodi_id', 'kriteria_id', 'user_id', 'status', 'skor', 'submitted_at', 'updated_at'];

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
}
