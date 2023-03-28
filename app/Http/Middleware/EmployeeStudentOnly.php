<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Session;

class EmployeeStudentOnly extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        if (Session::get('user_id')) {
            if (Session::get('user_info')['user_type'] == \App\Services\Employees\Employee::class || Session::get('user_info')['user_type'] == \App\Services\Students\Student::class) {
                return $next($request);
            }
        } else {
            return redirect(env('HOME_URL', '/'));
        }
    }
}
