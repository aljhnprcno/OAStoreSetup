<?php

namespace App\Services\Base;

use Carbon\Carbon;
use App\Services\Passwords\PasswordReset;
use Illuminate\Foundation\Auth\User as BaseAuthenticatable;
use Illuminate\Support\Str;

abstract class Authenticatable extends BaseAuthenticatable
{
    public function makePasswordResetRequest()
    {
        $token = Str::random(16);

        $passwordReset = new PasswordReset([
            'token' => $token,
            'parent_id' => $this->id,
            'expires_at' => Carbon::now()->addMinutes(30)
        ]);

        $passwordReset->save();
        $passwordReset->plain_token = $token;

        return $passwordReset;
    }
}
