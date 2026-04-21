<?php

namespace App\Http\Controllers\Admin;

use App\Models\PagePermission;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display permission management page
     */
    public function index(Request $request)
    {
        $navigationConfig = config('navigation');
        $routePermissions = [];

        // Extract routes from navigation config
        $routes = [];

        // Add main dashboard
        if (isset($navigationConfig['main']['route'])) {
            $routes[] = [
                'route' => $navigationConfig['main']['route'],
                'label' => $navigationConfig['main']['label'],
                'section' => 'Main',
            ];
        }

        // Add all section items
        if (isset($navigationConfig['sections'])) {
            foreach ($navigationConfig['sections'] as $section) {
                $sectionTitle = $section['title'] ?? 'Other';
                if (isset($section['items'])) {
                    foreach ($section['items'] as $item) {
                        if (isset($item['route'])) {
                            $routes[] = [
                                'route' => $item['route'],
                                'label' => $item['label'],
                                'section' => $sectionTitle,
                            ];
                        }
                    }
                }
            }
        }

        // Get/create permissions for each route
        foreach ($routes as $route) {
            $permission = PagePermission::where('route_name', $route['route'])->firstOrCreate(
                ['route_name' => $route['route']],
                [
                    'page_label' => $route['label'],
                    'allowed_roles' => [],
                ]
            );
            $permission->section = $route['section'];
            $routePermissions[] = $permission;
        }

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $routePermissions = array_filter(
                $routePermissions,
                fn($p) =>
                str_contains(strtolower($p->route_name), strtolower($search)) ||
                    str_contains(strtolower($p->page_label), strtolower($search))
            );
        }

        return view('admin.permissions.index', [
            'routePermissions' => $routePermissions,
            'availableRoles' => ['admin', 'dosen', 'validator'],
        ]);
    }

    /**
     * Toggle role access (AJAX)
     */
    public function toggleRole(Request $request, $routeName)
    {
        $validated = $request->validate([
            'role' => 'required|in:admin,dosen,validator',
            'enabled' => 'required|boolean',
        ]);

        $permission = PagePermission::firstOrCreate(
            ['route_name' => $routeName],
            [
                'page_label' => str_replace(['.', '-'], ' ', ucfirst($routeName)),
                'allowed_roles' => [],
            ]
        );

        $roles = $permission->allowed_roles ?? [];
        $role = $validated['role'];

        if ($validated['enabled']) {
            if (!in_array($role, $roles)) {
                $roles[] = $role;
            }
        } else {
            $roles = array_filter($roles, fn($r) => $r !== $role);
        }

        $permission->update(['allowed_roles' => $roles]);

        return response()->json(['success' => true]);
    }

    /**
     * Reset all permissions (set default: admin can access all)
     */
    public function reset()
    {
        $navigationConfig = config('navigation');

        // Get all navigation routes
        $routes = [];
        if (isset($navigationConfig['main']['route'])) {
            $routes[] = $navigationConfig['main']['route'];
        }
        if (isset($navigationConfig['sections'])) {
            foreach ($navigationConfig['sections'] as $section) {
                if (isset($section['items'])) {
                    foreach ($section['items'] as $item) {
                        if (isset($item['route'])) {
                            $routes[] = $item['route'];
                        }
                    }
                }
            }
        }

        // Reset: admin can access everything
        foreach ($routes as $route) {
            PagePermission::updateOrCreate(
                ['route_name' => $route],
                ['allowed_roles' => ['admin']]
            );
        }

        return back()->with('success', 'Izin akses direset ke default (Admin akses semua halaman)');
    }
}
