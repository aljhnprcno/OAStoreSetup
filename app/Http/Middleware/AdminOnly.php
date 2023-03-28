<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Session;

class AdminOnly extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        if (Session::get('user_info')['user_type'] == \App\Services\Users\User::class) {
            return $next($request);
        }
        abort(401);
    }
}
