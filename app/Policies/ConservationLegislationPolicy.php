<?php

namespace App\Policies;

use App\Models\ConservationLegislation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConservationLegislationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the conservation list.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ConservationLegislation  $conservationLegislation
     * @return mixed
     */
    public function view(User $user, ConservationLegislation $conservationLegislation)
    {
        return $user->hasAnyRole(['admin', 'curator']);
    }

    /**
     * Determine whether the user can create conservation lists.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasAnyRole(['admin']);
    }

    /**
     * Determine whether the user can update the conservation list.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ConservationLegislation  $conservationLegislation
     * @return mixed
     */
    public function update(User $user, ConservationLegislation $conservationLegislation)
    {
        return $user->hasAnyRole(['admin']);
    }

    /**
     * Determine whether the user can delete the conservation list.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ConservationLegislation  $conservationLegislation
     * @return mixed
     */
    public function delete(User $user, ConservationLegislation $conservationLegislation)
    {
        return $user->hasAnyRole(['admin']);
    }
}
