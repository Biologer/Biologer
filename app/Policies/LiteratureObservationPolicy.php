<?php

namespace App\Policies;

use App\LiteratureObservation;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LiteratureObservationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create literature observation.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view literature observation.
     *
     * @param  \App\User  $user
     * @param  \App\LiteratureObservation  $literatureObservation
     * @return mixed
     */
    public function view(User $user, LiteratureObservation $literatureObservation)
    {
        return true;
    }

    /**
     * Determine whether the user can view literature observation activity log.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewActivityLog(User $user)
    {
        return $user->hasAnyRole(['admin', 'curator']);
    }

    /**
     * Determine whether the user can create literature observation.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasAnyRole(['admin', 'curator']);
    }

    /**
     * Determine whether the user can update the literature observation.
     *
     * @param  \App\User  $user
     * @param  \App\LiteratureObservation  $literatureObservation
     * @return mixed
     */
    public function update(User $user, LiteratureObservation $literatureObservation)
    {
        return $user->hasAnyRole(['admin', 'curator']);
    }

    /**
     * Determine whether the user can delete the literature observation.
     *
     * @param  \App\User  $user
     * @param  \App\LiteratureObservation  $literatureObservation
     * @return mixed
     */
    public function delete(User $user, LiteratureObservation $literatureObservation)
    {
        return $user->hasAnyRole(['admin', 'curator']);
    }
}
