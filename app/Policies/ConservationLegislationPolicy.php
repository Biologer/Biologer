<?php

namespace App\Policies;

use App\ConservationLegislation;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConservationLegislationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the conservation list.
     *
     * @param  \App\User  $user
     * @param  \App\ConservationLegislation  $conservationLegislation
     * @return mixed
     */
    public function view(User $user, ConservationLegislation $conservationLegislation)
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
     * @param  \App\ConservationLegislation  $conservationLegislation
     * @return mixed
     */
    public function update(User $user, ConservationLegislation $conservationLegislation)
    {
        return $user->hasAnyRole(['admin']);
    }

    /**
     * Determine whether the user can delete the conservation list.
     *
     * @param  \App\User  $user
     * @param  \App\ConservationLegislation  $conservationLegislation
     * @return mixed
     */
    public function delete(User $user, ConservationLegislation $conservationLegislation)
    {
        return $user->hasAnyRole(['admin']);
    }
}
