<?php

namespace App\Services\LearningMaterials;

use Illuminate\Database\Eloquent\Model;

class ClassMaterial extends Model
{
    protected $fillable = ['course_code', 'title', 'program', 'description', 'employee_id', 'branch_code', 'subject_area_id', 'cover_image', 'availability'];

    public function learning_material() {
        return $this->belongsTo(LearningMaterial::class);
    }

    public function class_material_files() {
        return $this->hasMany(ClassMaterialFile::class);
    }

    public function subject_area()
    {
        return $this->belongsTo(SubjectArea::class);
    }

}
