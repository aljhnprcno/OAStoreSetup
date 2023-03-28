<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Session;

class CheckPermission extends Middleware
{
    public function handle($request, Closure $next, $permission, $guard = null)
    {
        if (Session::get('user_id')) {
            $permissions = is_array($permission)
                ? $permission
                : explode('|', $permission);

            foreach ($permissions as $permission) {
                if (in_array($permission, Session::get('permissions'))) {
                    return $next($request);
                }
            }
        }
        return response()->json(['text' => "You don't have permission for this request."], 403);
    }
}
