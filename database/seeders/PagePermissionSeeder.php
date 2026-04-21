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

        // Seed default permissions
        foreach ($routes as $route) {
            PagePermission::updateOrCreate(
                ['route_name' => $route['route']],
                [
                    'page_label' => $route['label'],
                    'allowed_roles' => ['admin'],
                ]
            );
        }

        $this->command->info('Page permissions seeded successfully!');
    }
}
