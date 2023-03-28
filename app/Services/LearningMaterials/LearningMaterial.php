<?php

namespace App\Services\LearningMaterials;

use Illuminate\Database\Eloquent\Model;

class LearningMaterial extends Model
{
    protected $fillable = ['class_material_id'];

    public function learning_material_requests() {
        return $this->hasMany(ClassMaterialRequest::class);
    }

    public function class_material() {
        return $this->belongsTo(ClassMaterial::class);
    }

}
