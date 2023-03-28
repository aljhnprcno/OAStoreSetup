<?php

namespace App\Services\Syllabus;

use Illuminate\Database\Eloquent\Model;

class SyllabusUnit extends Model
{
    protected $fillable = ['syllabus_department_id', 'description'];

    function department() {
        return $this->belongsTo(SyllabusDepartment::class, 'syllabus_department_id');
    }

}
