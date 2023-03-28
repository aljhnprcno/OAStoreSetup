<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Session;

class CheckAuth extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        if (Session::get('user_id')) {
            return $next($request);
        } else {
            return redirect(env('HOME_URL', '/'));
        }
    }
}
