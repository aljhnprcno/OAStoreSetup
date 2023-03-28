<?php

namespace App\Services\LearningMaterials;

use Illuminate\Database\Eloquent\Model;

class ClassMaterialRequest extends Model
{
    protected $fillable = [
        'class_material_id',
        'type',
        'entity_id',
        'branch_code',
        'is_accepted',
    ];

    public function class_material() {
        return $this->belongsTo(ClassMaterial::class);
    }

}
