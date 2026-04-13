<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateItem extends Model
{
    protected $table = 'template_item';
    protected $primaryKey = 'template_id';
    public $timestamps = false;
    protected $fillable = ['kriteria_id', 'tipe', 'label', 'hint', 'wajib', 'bobot', 'nilai_min_numerik', 'urutan'];

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_id');
    }

    public function submissionItems()
    {
        return $this->hasMany(SubmissionItem::class, 'template_item_id');
    }
}
