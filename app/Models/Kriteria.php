<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    protected $table = 'kriteria';
    protected $primaryKey = 'kriteria_id';
    public $timestamps = false;
    protected $fillable = ['parent_id', 'kode', 'nama', 'deskripsi', 'level', 'bobot', 'urutan'];

    public function parent()
    {
        return $this->belongsTo(Kriteria::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Kriteria::class, 'parent_id')->orderBy('urutan');
    }

    public function templateItems()
    {
        return $this->hasMany(TemplateItem::class, 'kriteria_id');
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class, 'kriteria_id');
    }
}
