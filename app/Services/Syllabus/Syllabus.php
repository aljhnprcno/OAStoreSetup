<?php

namespace App\Services\Syllabus;

use App\Services\LearningMaterials\SubjectArea;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Str;

class Syllabus extends Model
{
    protected $fillable = ['course_code', 'title', 'program', 'description', 'employee_id', 'branch_code', 'subject_area_id', 'cover_image', 'syllabus_department_id', 'syllabus_unit_id'];

    public function syllabus_files() {
        return $this->hasMany(SyllabusFile::class);
    }

    public function subject_area()
    {
        return $this->belongsTo(SubjectArea::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($syllabus) {
            $syllabus->slug = Str::slug($syllabus->course_code) . '-' .Uuid::uuid1()->toString();
        });
    }
}
