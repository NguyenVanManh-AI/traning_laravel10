<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        // $user = Auth::guard('admin_api')->user() ?? Auth::guard('user_api')->user();
        $user = Auth::guard('user_api')->user();
        if (!$user) {
            return response()->json(['status' => 'Unauthorized'], 401);
        }

        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        return response()->json(['status' => 'Forbidden'], 403);
    }
}
