<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmissionItem extends Model
{
    protected $table = 'submission_item';
    protected $primaryKey = 'subitem_id';
    public $timestamps = false;
    protected $fillable = ['submission_id', 'template_item_id', 'nilai_checklist', 'nilai_teks', 'nilai_numerik'];

    public function submission()
    {
        return $this->belongsTo(Submission::class, 'submission_id');
    }

    public function templateItem()
    {
        return $this->belongsTo(TemplateItem::class, 'template_item_id');
    }

    public function dokumen()
    {
        return $this->hasMany(Dokumen::class, 'subitem_id');
    }
}
