<?php

namespace App\Services\LearningMaterials;

use Illuminate\Database\Eloquent\Model;

class SubjectArea extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'branch_code'];


    public function class_material()
    {
        return $this->hasOne(ClassMaterial::class);
    }
}
