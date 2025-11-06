<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed ...$roles
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // user_level = 1 → Admin
        // user_level = 2 → Cashier
        if (!in_array($user->user_level, $roles)) {
            abort(403, 'Unauthorized Access.');
        }

        return $next($request);
    }
}
