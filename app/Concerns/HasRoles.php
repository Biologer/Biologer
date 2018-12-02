<?php

namespace App\Concerns;

use App\Role;

trait HasRoles
{
    /**
     * Relation to roles that the user has.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Scope the query to get only users with role of curator.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAdmins($query)
    {
        return $query->whereHas('roles', function ($query) {
            return $query->where('name', 'admin');
        });
    }

    /**
     * Scope the query to get only users with role of curator.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCurators($query)
    {
        return $query->whereHas('roles', function ($query) {
            return $query->where('name', 'curator');
        });
    }

    /**
     * Get names of roles the user has.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function roleNames()
    {
        return $this->roles->pluck('name');
    }

    /**
     * Check if user has given role.
     *
     * @param  string  $role
     * @return bool
     */
    public function hasRole($role)
    {
        return $this->roleNames()->contains($role);
    }

    /**
     * Check if user has one or more of the given roles.
     *
     * @param  array  $roles
     * @return bool
     */
    public function hasAnyRole(array $roles)
    {
        return $this->roleNames()->intersect($roles)->isNotEmpty();
    }

    /**
     * Check if user has all the given roles.
     *
     * @param  array  $roles
     * @return bool
     */
    public function hasAllRoles(array $roles)
    {
        return count($roles) === $this->roleNames()->intersect($roles)->count();
    }

    /**
     * Assign given roles to user.
     *
     * @param  array|string  $roles
     * @return $this
     */
    public function assignRoles($roles)
    {
        if (is_string($roles)) {
            $roles = func_get_args();
        }

        $this->roles()->attach(Role::findAllByName($roles));

        return $this->load('roles');
    }

    /**
     * Revoke given roles to user.
     *
     * @param  array|string  $roles
     * @return $this
     */
    public function revokeRoles($roles)
    {
        if (is_string($roles)) {
            $roles = func_get_args();
        }

        $this->roles()->detach(Role::findAllByName($roles));

        return $this->load('roles');
    }
}
