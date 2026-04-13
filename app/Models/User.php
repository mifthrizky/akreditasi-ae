<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $timestamps = true;

    protected $fillable = ['name', 'email', 'password', 'role'];
    protected $hidden = ['password'];

    public function prodis()
    {
        return $this->belongsToMany(ProgramStudi::class, 'user_prodi', 'user_id', 'prodi_id');
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class, 'user_id');
    }

    public function validasis()
    {
        return $this->hasMany(Validasi::class, 'validator_id');
    }
}
