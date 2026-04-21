<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PagePermission extends Model
{
    protected $table = 'page_permissions';

    protected $fillable = [
        'route_name',
        'page_label',
        'allowed_roles',
        'description',
    ];

    protected $casts = [
        'allowed_roles' => 'array',
    ];

    /**
     * Check if a role has access to this page
     */
    public function hasRole($role)
    {
        return in_array($role, $this->allowed_roles ?? []);
    }

    /**
     * Add role to allowed_roles
     */
    public function grantRole($role)
    {
        $roles = $this->allowed_roles ?? [];
        if (!in_array($role, $roles)) {
            $roles[] = $role;
            $this->allowed_roles = $roles;
        }
        return $this;
    }

    /**
     * Remove role from allowed_roles
     */
    public function revokeRole($role)
    {
        $roles = $this->allowed_roles ?? [];
        $this->allowed_roles = array_filter($roles, fn($r) => $r !== $role);
        return $this;
    }

    /**
     * Get all pages accessible by a role
     */
    public static function byRole($role)
    {
        return static::where('allowed_roles', 'like', '%' . $role . '%')->get();
    }
}
