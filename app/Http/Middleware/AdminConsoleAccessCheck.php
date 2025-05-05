<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminConsoleAccessCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $role = session('role') ?? '';
        $allowed_roles = ['admin'];
        if ($request->user()->is_superuser || ($role && in_array($role->slug, $allowed_roles))) {
            return $next($request);
        }
        abort(401);
    }
}
