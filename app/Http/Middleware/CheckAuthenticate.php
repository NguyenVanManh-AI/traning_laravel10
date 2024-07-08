<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return $next($request);
            }
        }

        foreach ($guards as $guard) {
            if ($guard == 'user') {
                return redirect()->route('user.login.view');
            }

            if ($guard == 'admin') {
                return redirect()->route('admin.login.view');
            }

            if ($guard == 'admin_api') {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }

            if ($guard == 'user_api') {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }
        }
    }
}
