<?php

namespace App\Services\Passwords;

use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PasswordReset extends Model
{
    use SoftDeletes;

    public $incrementing = false;

    protected $fillable = [
        'token',
        'expires_at',
        'parent_id',
        'parent_type'
    ];

    protected $dates = [
        'expires_at',
        'deleted_at'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (self $model) {
            $model->{$model->getKeyName()} = Uuid::uuid1()->toString();
        });
    }

    public function setTokenAttribute($token)
    {
        if ($token !== null) {
            $token = Hash::make($token);
        }

        $this->attributes['token'] = $token;
    }

    public function isTokenValid($token): bool
    {
        return Hash::check($token, $this->attributes['token']);
    }

    public function hasExpired(): bool
    {
        return $this->expires_at->lt(now());
    }

    public function parent(): MorphTo
    {
        return $this->morphTo();
    }
}
