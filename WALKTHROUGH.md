# PagePermission Seeder Setup Walkthrough

## Overview

This guide explains how the PagePermissionSeeder has been updated to automatically set role-based access permissions when running `php artisan migrate:fresh --seed`.

---

## What Changed

### Before

The seeder assigned **only `admin` role** to all routes by default:

```php
'allowed_roles' => ['admin'],
```

### After

The seeder now uses a **role permissions mapping** to assign specific roles to each route based on their function:

```php
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
```

---

## Role Permission Structure

### Permission Levels Explained

| Route                         | Roles                   | Purpose                                         |
| ----------------------------- | ----------------------- | ----------------------------------------------- |
| **dashboard**                 | admin, dosen, validator | All roles can view main dashboard               |
| **admin.program-studi.index** | admin                   | Admin-only program study management             |
| **admin.kriteria.index**      | admin                   | Admin-only criteria & CPL management            |
| **admin.users.index**         | admin                   | Admin-only user management                      |
| **admin.permissions.index**   | admin                   | Admin-only permission management                |
| **dosen.prodi.index**         | admin, dosen            | Lecturers & admins can manage their programs    |
| **validator.antrian.index**   | admin, validator        | Validators & admins can manage submission queue |
| **validator.riwayat.index**   | admin, validator        | Validators & admins can view validation history |

---

## How It Works

### Step 1: Extract Routes from Navigation Config

```php
// Gets all routes defined in config/navigation.php
$routes[] = [
    'route' => 'dashboard',
    'label' => 'Dashboard',
];
```

### Step 2: Define Role Permissions Mapping

```php
$rolePermissions = [
    'route_name' => ['role1', 'role2', ...],
    // Each route maps to an array of allowed roles
];
```

### Step 3: Create/Update Permission Records

```php
foreach ($routes as $route) {
    $routeName = $route['route'];
    $allowedRoles = $rolePermissions[$routeName] ?? ['admin'];  // Default to admin

    PagePermission::updateOrCreate(
        ['route_name' => $routeName],
        [
            'page_label' => $route['label'],
            'allowed_roles' => $allowedRoles,
        ]
    );
}
```

**Key Logic:**

- For each route, lookup its roles in `$rolePermissions` mapping
- If not found, default to `['admin']` (fallback for new routes)
- Create or update the PagePermission record

---

## Using the Seeder

### Run Fresh Migration with Seed

```bash
php artisan migrate:fresh --seed
```

This command:

1. ✅ Drops all tables
2. ✅ Runs all migrations
3. ✅ Executes all seeders (including PagePermissionSeeder)
4. ✅ Sets permissions according to `$rolePermissions` mapping

### Database Result

After seeding, your `page_permissions` table will have:

- 8 records with specific role permissions
- All roles properly assigned per route
- No manual permission configuration needed

---

## Adding New Routes

### To Add a New Route Permission

**1. Add Route to Navigation** (`config/navigation.php`)

```php
'sections' => [
    [
        'title' => 'New Section',
        'items' => [
            [
                'route' => 'new.feature.index',
                'label' => 'New Feature',
            ],
        ],
    ],
],
```

**2. Add Permission Mapping** (`database/seeders/PagePermissionSeeder.php`)

```php
$rolePermissions = [
    // ... existing routes ...
    'new.feature.index' => ['admin', 'dosen'],  // Add your mapping
];
```

**3. Run Migration**

```bash
php artisan migrate:fresh --seed
```

---

## Database Schema

The `page_permissions` table stores:
| Column | Type | Purpose |
|--------|------|---------|
| id | bigint | Primary key |
| route_name | string | Unique route identifier |
| page_label | string | Display name |
| **allowed_roles** | json | Array of role strings |
| description | text | Optional notes |
| created_at | timestamp | Created date |
| updated_at | timestamp | Last updated date |

---

## Model Methods Available

The `PagePermission` model provides helper methods:

```php
// Check if role has access
$permission->hasRole('admin');  // Returns true/false

// Grant role access
$permission->grantRole('dosen')->save();

// Revoke role access
$permission->revokeRole('dosen')->save();

// Get all pages accessible by a role
PagePermission::byRole('dosen')->get();
```

---

## Fallback Behavior

Routes **not explicitly defined** in `$rolePermissions` will default to `['admin']`:

```php
$allowedRoles = $rolePermissions[$routeName] ?? ['admin'];  // ← Fallback
```

This ensures:

- ✅ New routes remain admin-only by default
- ✅ No accidental role exposure
- ✅ Explicit mapping prevents mistakes

---

## Common Tasks

### Modify Existing Permission

Edit the mapping:

```php
$rolePermissions = [
    'dashboard' => ['admin', 'dosen', 'validator'],  // ← Change here
];
```

Then re-run: `php artisan migrate:fresh --seed`

### Grant New Role to Route

```php
$rolePermissions['dashboard'] => ['admin', 'dosen', 'validator', 'new_role'];
```

### Remove Role from Route

```php
$rolePermissions['admin.users.index'] => ['admin'];  // Keep admin only
```

---

## Verification

After running the seeder, verify in database:

```bash
php artisan tinker

# Check specific route
PagePermission::where('route_name', 'dashboard')->first();

# Check all permissions
PagePermission::all();

# Check permissions by role
PagePermission::byRole('dosen')->get();
```

---

## Summary

✅ **Automatic Setup** - Permissions are set during migration
✅ **Role-Based** - Different routes have different role access
✅ **Easy Maintenance** - Modify `$rolePermissions` array to change access
✅ **Safe Defaults** - Unmapped routes default to admin-only
✅ **Scalable** - Add new routes without code changes beyond mapping
