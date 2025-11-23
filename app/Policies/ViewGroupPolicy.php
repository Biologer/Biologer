<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ViewGroup;
use Illuminate\Auth\Access\HandlesAuthorization;

class ViewGroupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ViewGroup  $model
     * @return mixed
     */
    public function view(User $user, ViewGroup $model)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ViewGroup  $model
     * @return mixed
     */
    public function update(User $user, ViewGroup $model)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ViewGroup  $model
     * @return mixed
     */
    public function delete(User $user, ViewGroup $model)
    {
        return $user->hasRole('admin');
    }
}
