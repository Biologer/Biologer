<?php

namespace App\Concerns;

use App\Role;

trait HasRoles
{
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    protected function roleNames()
    {
        return $this->roles->pluck('name');
    }

    public function hasRole($role)
    {
        return $this->roleNames()->contains($role);
    }

    public function hasAnyRole(array $roles)
    {
        return $this->roleNames()->intersect($roles)->isNotEmpty();
    }

    public function hasAllRoles(array $roles)
    {
        return count($roles) === $this->roleNames()->intersect($roles)->count();
    }
}
