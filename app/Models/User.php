<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property Collection $prodis
 * @property Collection $submissions
 * @property Collection $validasis
 */
class User extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $timestamps = true;

    protected $fillable = ['nama', 'email', 'password', 'role'];
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
