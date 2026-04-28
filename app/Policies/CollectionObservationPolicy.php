<?php

namespace App\Policies;

use App\CollectionObservation;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CollectionObservationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create collection observation.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view collection observation.
     *
     * @param  \App\User  $user
     * @param  \App\CollectionObservation  $collectionObservation
     * @return mixed
     */
    public function view(User $user, CollectionObservation $collectionObservation)
    {
        return true;
    }

    /**
     * Determine whether the user can view collection observation activity log.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewActivityLog(User $user)
    {
        return $user->hasAnyRole(['admin', 'curator']);
    }

    /**
     * Determine whether the user can create collection observation.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasAnyRole(['admin', 'curator']);
    }

    /**
     * Determine whether the user can update the collection observation.
     *
     * @param  \App\User  $user
     * @param  \App\CollectionObservation  $collectionObservation
     * @return mixed
     */
    public function update(User $user, CollectionObservation $collectionObservation)
    {
        return $user->hasAnyRole(['admin', 'curator']);
    }

    /**
     * Determine whether the user can delete the collection observation.
     *
     * @param  \App\User  $user
     * @param  \App\CollectionObservation  $collectionObservation
     * @return mixed
     */
    public function delete(User $user, CollectionObservation $collectionObservation)
    {
        return $user->hasAnyRole(['admin', 'curator']);
    }
}
