<?php

namespace App\Services\Syllabus;

use Illuminate\Database\Eloquent\Model;

class SyllabusDepartment extends Model
{
    protected $fillable = ['code', 'description'];

    function units() {
        return $this->hasMany(SyllabusUnit::class);
    }

}
