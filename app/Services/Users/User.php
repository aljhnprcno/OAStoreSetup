<?php

namespace App\Services\Users;

use Ramsey\Uuid\Uuid;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Services\Pictures\Traits\UploadableModelPictureTrait;
use App\Services\Base\Scopes\OrderByCreatedAtScope;
use App\Services\Base\Authenticatable;
use App\Services\Base\Traits;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens,
        HasRoles,
        SoftDeletes,
        Notifiable,
        Traits\HashedPasswordTrait,
        Traits\GeneratesPasswordTrait,
        UploadableModelPictureTrait;

    public $incrementing = false;

    protected $appends = [
        'name'
    ];

    protected $with = [
        'picture'
    ];

    protected $fillable = [
        'fname',
        'mname',
        'lname',
        'ext_name',
        'email',
        'password',
        'require_password_change'
    ];

    protected $casts = [
        'id' => 'char',
        'require_password_change' => 'bool'
    ];

    protected $guard_name = 'api';

    protected $hidden = [
        'picture_id',
        'password'
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new OrderByCreatedAtScope);

        static::creating(function (self $model) {
            $model->{$model->getKeyName()} = Uuid::uuid1()->toString();
        });
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        foreach ($this->roles as $role) {
            if ($role->isAdmin()) {
                return true;
            }
        }

        return false;
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
        return 'user-avatar';
    }

    public function getPictureFullSize(): array
    {
        return [512, 512];
    }

    public function getPictureThumbnailSize(): array
    {
        return [64, 64];
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getNameAttribute(): string
    {
        return sprintf('%s %s %s %s', $this->fname, $this->mname, $this->lname, $this->ext_name);
    }
}

