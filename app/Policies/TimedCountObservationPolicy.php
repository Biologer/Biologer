<?php

namespace App\Policies;

use App\Models\TimedCountObservation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TimedCountObservationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the fieldObservation.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TimedCountObservation  $timedCountObservation
     * @return mixed
     */
    public function view(User $user, TimedCountObservation $timedCountObservation)
    {
        return $user->hasAnyRole(['admin', 'curator']);
    }

    /**
     * Determine whether the user can list the field observation.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return $user->hasAnyRole(['admin', 'curator']);
    }

    /**
     * Determine whether the user can create field observation.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the field observation.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TimedCountObservation  $timedCountObservation
     * @return mixed
     */
    public function update(User $user, TimedCountObservation $timedCountObservation)
    {
        return $user->hasRole('admin')
            || $this->isCurator($user, $timedCountObservation)
            || $timedCountObservation->isCreatedBy($user);
    }

    /**
     * Determine whether the user can delete the field observation.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TimedCountObservation  $timedCountObservation
     * @return mixed
     */
    public function delete(User $user, TimedCountObservation $timedCountObservation)
    {
        return $timedCountObservation->isCreatedBy($user) || $user->hasRole('admin');
    }

}
