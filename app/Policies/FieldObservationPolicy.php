<?php

namespace App\Policies;

use App\User;
use App\FieldObservation;
use Illuminate\Auth\Access\HandlesAuthorization;

class FieldObservationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the fieldObservation.
     *
     * @param  \App\User  $user
     * @param  \App\FieldObservation  $fieldObservation
     * @return mixed
     */
    public function view(User $user, FieldObservation $fieldObservation)
    {
        return $fieldObservation->isAtLeastPartiallyOpenData() ||
            $user->hasAnyRole(['admin', 'curator']);
    }

    /**
     * Determine whether the user can list the field observation.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return $user->hasAnyRole(['admin', 'curator']);
    }

    /**
     * Determine whether the user can create field observation.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the field observation.
     *
     * @param  \App\User  $user
     * @param  \App\FieldObservation  $fieldObservation
     * @return mixed
     */
    public function update(User $user, FieldObservation $fieldObservation)
    {
        return $user->hasRole('admin')
            || $this->isCurator($user, $fieldObservation)
            || $fieldObservation->isCreatedBy($user);
    }

    /**
     * Determine whether the user can delete the field observation.
     *
     * @param  \App\User  $user
     * @param  \App\FieldObservation  $fieldObservation
     * @return mixed
     */
    public function delete(User $user, FieldObservation $fieldObservation)
    {
        return $fieldObservation->isCreatedBy($user) || $user->hasRole('admin');
    }

    /**
     * Determinte whether the user can approve the field observation.
     *
     * @param  \App\User  $user
     * @param  \App\FieldObservation  $fieldObservation
     * @return bool
     */
    public function approve(User $user, FieldObservation $fieldObservation)
    {
        return $this->isCurator($user, $fieldObservation);
    }

    /**
     * Determinte whether the user can approve the field observation.
     *
     * @param  \App\User  $user
     * @param  \App\FieldObservation  $fieldObservation
     * @return bool
     */
    public function markAsUnidentifiable(User $user, FieldObservation $fieldObservation)
    {
        return $this->isCurator($user, $fieldObservation);
    }

    /**
     * Determinte whether the user can move the field observation to pending.
     *
     * @param  \App\User  $user
     * @param  \App\FieldObservation  $fieldObservation
     * @return bool
     */
    public function moveToPending(User $user, FieldObservation $fieldObservation)
    {
        return $this->isCurator($user, $fieldObservation);
    }

    /**
     * Check if the user is curator for the observed taxon.
     *
     * @param  \App\User  $user
     * @param  \App\FieldObservation  $fieldObservation
     * @return bool
     */
    protected function isCurator(User $user, FieldObservation $fieldObservation)
    {
        return $user->hasRole('curator') && $fieldObservation->shouldBeCuratedBy($user);
    }
}
