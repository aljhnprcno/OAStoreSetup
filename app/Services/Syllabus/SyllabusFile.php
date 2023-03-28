<?php

namespace App\Services\Syllabus;

use Illuminate\Database\Eloquent\Model;

class SyllabusFile extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'syllabus_id',
        'file_name',
        'path',
        'type',
    ];
    
    public function syllabus() {
        return $this->belongsTo(Syllabus::class);
    }
}
