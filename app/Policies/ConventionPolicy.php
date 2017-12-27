<?php

namespace App\Policies;

use App\User;
use App\Convention;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConventionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the convention.
     *
     * @param  \App\User  $user
     * @param  \App\Convention  $convention
     * @return mixed
     */
    public function view(User $user, Convention $convention)
    {
        return $user->hasAnyRole(['admin', 'curator']);
    }

    /**
     * Determine whether the user can create conventions.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasAnyRole(['admin', 'curator']);
    }

    /**
     * Determine whether the user can update the convention.
     *
     * @param  \App\User  $user
     * @param  \App\Convention  $convention
     * @return mixed
     */
    public function update(User $user, Convention $convention)
    {
        return $user->hasAnyRole(['admin', 'curator']);
    }

    /**
     * Determine whether the user can delete the convention.
     *
     * @param  \App\User  $user
     * @param  \App\Convention  $convention
     * @return mixed
     */
    public function delete(User $user, Convention $convention)
    {
        return $user->hasAnyRole(['admin', 'curator']);
    }
}
