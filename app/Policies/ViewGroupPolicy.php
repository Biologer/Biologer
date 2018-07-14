<?php

namespace App\Policies;

use App\User;
use App\ViewGroup;
use Illuminate\Auth\Access\HandlesAuthorization;

class ViewGroupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\ViewGroup  $model
     * @return mixed
     */
    public function view(User $user, ViewGroup $model)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\ViewGroup  $model
     * @return mixed
     */
    public function update(User $user, ViewGroup $model)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\ViewGroup  $model
     * @return mixed
     */
    public function delete(User $user, ViewGroup $model)
    {
        return $user->hasRole('admin');
    }
}
