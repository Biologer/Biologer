<?php

namespace App\Policies;

use App\RedList;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RedListPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the redList.
     *
     * @param  \App\User  $user
     * @param  \App\RedList  $redList
     * @return mixed
     */
    public function view(User $user, RedList $redList)
    {
        return $user->hasAnyRole(['admin', 'curator']);
    }

    /**
     * Determine whether the user can create redLists.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasAnyRole(['admin', 'curator']);
    }

    /**
     * Determine whether the user can update the redList.
     *
     * @param  \App\User  $user
     * @param  \App\RedList  $redList
     * @return mixed
     */
    public function update(User $user, RedList $redList)
    {
        return $user->hasAnyRole(['admin', 'curator']);
    }

    /**
     * Determine whether the user can delete the redList.
     *
     * @param  \App\User  $user
     * @param  \App\RedList  $redList
     * @return mixed
     */
    public function delete(User $user, RedList $redList)
    {
        return $user->hasAnyRole(['admin', 'curator']);
    }
}
