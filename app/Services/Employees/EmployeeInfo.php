<?php

namespace App\Services\Employees;

use App\Services\Pictures\Traits\UploadableModelPictureTrait;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class EmployeeInfo extends Model
{
    use UploadableModelPictureTrait;

    public $fillable = [
        'picture_id',
        'employee_id',
        'religion_id',
        'province_id',
        'branch_code',
        'is_picture_confidential',
        'units_load',
        'full_name_sss',
        'civil_status',
        'home_phone',
        'email_2',
        'province_address',
        'province_phone',
        'emergency_person',
        'emergency_relationship',
        'emergency_phone'
    ];

    protected $casts = [
        'id' => 'char'
    ];

    protected $with = [
        'picture'
    ];

    protected $hidden = [
        'picture_id',
        'created_at',
        'updated_at'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (self $model) {
            $model->{$model->getKeyName()} = Uuid::uuid1()->toString();
        });

        static::deleting(function (self $model) {
            $model->picture()->delete();
        });
    }

    public function getDefaultFullPath(): string
    {
        return '/assets/img/default-picture.png';
    }

    public function getDefaultThumbnailPath(): string
    {
        return '/assets/img/default-picture-thumb.png';
    }

    public function getUploadNamespace(): string
    {
        return 'employees';
    }

    public function getPictureFullSize(): array
    {
        return [1080];
    }

    public function getPictureThumbnailSize(): array
    {
        return [256, 256];
    }
}
