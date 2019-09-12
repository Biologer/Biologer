<?php

namespace App\Policies;

use App\Taxon;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaxonPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the taxon.
     *
     * @param  \App\User  $user
     * @param  \App\Taxon  $taxon
     * @return mixed
     */
    public function view(User $user, Taxon $taxon)
    {
        return true;
    }

    /**
     * Determine whether the user can view the taxon.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can create taxons.
     *
     * @param  \App\User  $user
     * @param  int|null  $parentId
     * @return mixed
     */
    public function create(User $user, $parentId = null)
    {
        return $user->hasRole('admin') || $this->canCreateWithParent($user, $parentId);
    }

    /**
     * Check if user can create taxon whos parent has given ID.
     *
     * @param  \App\User  $user
     * @param  int  $parentId
     * @return bool
     */
    protected function canCreateWithParent(User $user, $parentId)
    {
        return ($taxon = Taxon::find($parentId)) ? $taxon->isCuratedBy($user) : false;
    }

    /**
     * Determine whether the user can update the taxon.
     *
     * @param  \App\User  $user
     * @param  \App\Taxon  $taxon
     * @return mixed
     */
    public function update(User $user, Taxon $taxon)
    {
        return $user->hasRole('admin') || $taxon->isCuratedBy($user);
    }

    /**
     * Determine whether the user can delete the taxon.
     *
     * @param  \App\User  $user
     * @param  \App\Taxon  $taxon
     * @return mixed
     */
    public function delete(User $user, Taxon $taxon)
    {
        return $user->hasRole('admin') || $taxon->isCuratedBy($user);
    }
}
