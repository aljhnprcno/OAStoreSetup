<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Session;

class AdminEmployeeOnly
{
    public function handle($request, Closure $next, ...$guards)
    {
        if (Session::get('user_id')) {
            if (Session::get('user_info')['user_type'] == \App\Services\Users\User::class || Session::get('user_info')['user_type'] == \App\Services\Employees\Employee::class) {
                return $next($request);
            }
        }
        return redirect(env('HOME_URL', '/'));
    }
}
