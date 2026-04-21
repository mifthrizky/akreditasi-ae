<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\PagePermission;
use Illuminate\Support\Facades\Auth;

class CheckPagePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip permission check for unauthenticated users
        // Let the 'auth' middleware on individual routes handle login redirects
        if (!Auth::check()) {
            return $next($request);
        }

        $routeName = $request->route()->getName();
        $userRole = Auth::user()->role;

        // Group all dashboards under "dashboard" permission identifier
        $checkRouteName = $routeName;
        if ($checkRouteName && str_ends_with($checkRouteName, '.dashboard')) {
            $checkRouteName = 'dashboard';
        }

        // Get permission for this route
        $permission = \App\Models\PagePermission::where('route_name', $checkRouteName)->first();

        // If no permission defined, allow (backwards compatibility)
        if (!$permission) {
            return $next($request);
        }

        // Check if user role has access
        if (!$permission->hasRole($userRole)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini');
        }

        return $next($request);
    }
}
