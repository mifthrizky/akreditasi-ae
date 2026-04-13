<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    protected $table = 'dokumen';
    protected $primaryKey = 'dokumen_id';
    public $timestamps = false;
    protected $fillable = ['subitem_id', 'nama_file', 'path_file', 'tipe_file', 'ukuran_file', 'uploaded_at'];

    public function submissionItem()
    {
        return $this->belongsTo(SubmissionItem::class, 'subitem_id');
    }
}
