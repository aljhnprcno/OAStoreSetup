<?php

namespace App\Services\Base\Traits;

use Illuminate\Support\Str;

trait GeneratesPasswordTrait
{
    /**
     * Generate random password
     *
     * @param $requireChange boolean Require password change
     *
     * @return string
     */
    public function generatePassword($requireChange = true): string
    {
        $password = Str::random(8);

        $this->password = $password;
        $this->require_password_change = $requireChange;

        return $password;
    }
}
