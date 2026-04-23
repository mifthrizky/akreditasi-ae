<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PagePermission;

class PagePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $navigationConfig = config('navigation');

        // Define role permissions mapping
        $rolePermissions = [
            'dashboard' => ['admin', 'dosen', 'validator'],
            'admin.program-studi.index' => ['admin'],
            'admin.kriteria.index' => ['admin'],
            'admin.users.index' => ['admin'],
            'admin.permissions.index' => ['admin'],
            'dosen.prodi.index' => ['admin', 'dosen'],
            'validator.antrian.index' => ['admin', 'validator'],
            'validator.riwayat.index' => ['admin', 'validator'],
        ];

        // Get all navigation routes
        $routes = [];

        // Add main dashboard
        if (isset($navigationConfig['main']['route'])) {
            $routes[] = [
                'route' => $navigationConfig['main']['route'],
                'label' => $navigationConfig['main']['label'],
            ];
        }

        // Add all section items
        if (isset($navigationConfig['sections'])) {
            foreach ($navigationConfig['sections'] as $section) {
                if (isset($section['items'])) {
                    foreach ($section['items'] as $item) {
                        if (isset($item['route'])) {
                            $routes[] = [
                                'route' => $item['route'],
                                'label' => $item['label'],
                            ];
                        }
                    }
                }
            }
        }

        // Seed permissions with role mappings
        foreach ($routes as $route) {
            $routeName = $route['route'];
            $allowedRoles = $rolePermissions[$routeName] ?? ['admin'];

            PagePermission::updateOrCreate(
                ['route_name' => $routeName],
                [
                    'page_label' => $route['label'],
                    'allowed_roles' => $allowedRoles,
                ]
            );
        }

        $this->command->info('Page permissions seeded successfully!');
    }
}
