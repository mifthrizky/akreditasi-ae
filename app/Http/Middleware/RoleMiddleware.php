<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Check if user has required role(s)
     *
     * Usage in routes:
     * Route::middleware('role:admin')->group(...)
     * Route::middleware('role:dosen,validator')->group(...) // multiple roles
     */
    public function handle(Request $request, Closure $next, ...$roles): mixed
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $userRole = Auth::user()->role;

        // Allow admin to bypass role checks
        if ($userRole === 'admin') {
            return $next($request);
        }

        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        abort(403, 'Anda tidak memiliki akses ke halaman ini');
    }
}
