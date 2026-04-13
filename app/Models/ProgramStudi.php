<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramStudi extends Model
{
    protected $table = 'program_studi';
    protected $primaryKey = 'prodi_id';
    public $timestamps = false;
    protected $fillable = ['kode', 'nama', 'jurusan'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_prodi', 'prodi_id', 'user_id');
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class, 'prodi_id');
    }

    public function laporans()
    {
        return $this->hasMany(Laporan::class, 'prodi_id');
    }
}
