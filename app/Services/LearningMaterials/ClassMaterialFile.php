<?php

namespace App\Services\LearningMaterials;

use Illuminate\Database\Eloquent\Model;

class ClassMaterialFile extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'class_material_id',
        'file_name',
        'path',
        'type',
    ];
    
    public function class_material() {
        return $this->belongsTo(ClassMaterial::class);
    }

}
