<?php

namespace App\Services\Users;

use Ramsey\Uuid\Uuid;
use Illuminate\Notifications\Notifiable;
use App\Services\Base\Scopes\OrderByCreatedAtScope;
use App\Services\Base\Authenticatable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasPermissions;

class BranchUser extends Authenticatable
{
    use HasApiTokens, HasPermissions, Notifiable;

    public $incrementing = false;

    protected $guard_name = 'api';

    protected $fillable = [
        'parent_id',
        'parent_type',
        'branch_code'
    ];

    protected $casts = [
        'id' => 'char'
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
}

