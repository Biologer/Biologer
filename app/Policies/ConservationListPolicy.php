<?php

namespace App\Policies;

use App\User;
use App\ConservationList;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConservationListPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the conservation list.
     *
     * @param  \App\User  $user
     * @param  \App\ConservationList  $conservationList
     * @return mixed
     */
    public function view(User $user, ConservationList $conservationList)
    {
        return $user->hasAnyRole(['admin', 'curator']);
    }

    /**
     * Determine whether the user can create conservation lists.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasAnyRole(['admin']);
    }

    /**
     * Determine whether the user can update the conservation list.
     *
     * @param  \App\User  $user
     * @param  \App\ConservationList  $conservationList
     * @return mixed
     */
    public function update(User $user, ConservationList $conservationList)
    {
        return $user->hasAnyRole(['admin']);
    }

    /**
     * Determine whether the user can delete the conservation list.
     *
     * @param  \App\User  $user
     * @param  \App\ConservationList  $conservationList
     * @return mixed
     */
    public function delete(User $user, ConservationList $conservationList)
    {
        return $user->hasAnyRole(['admin']);
    }
}
