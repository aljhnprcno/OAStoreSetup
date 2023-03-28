<?php

namespace App\Services\Syllabus;

use Illuminate\Database\Eloquent\Model;

class SyllabusRequest extends Model
{
    protected $fillable = [
        'syllabus_id',
        'type',
        'entity_id',
        'branch_code',
        'is_accepted',
    ];

    public function syllabus() {
        return $this->belongsTo(Syllabus::class);
    }
}
