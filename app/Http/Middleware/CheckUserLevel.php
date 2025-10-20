<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserLevel
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next, ...$levels)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // If user level is not allowed → redirect to correct dashboard
        if (!in_array($user->user_level, $levels)) {
            // Admin level = 1
            if ($user->user_level == 1) {
                return redirect()->route('dashboard');
            }

            // Regular user level = 2 (or others)
            return redirect()->route('user.dashboard');
        }

        return $next($request);
    }
}
