<?php

namespace App\Policies;

use App\Models\Taxon;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaxonPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the taxon.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Taxon  $taxon
     * @return mixed
     */
    public function view(User $user, Taxon $taxon)
    {
        return true;
    }

    /**
     * Determine whether the user can view the taxon.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can create taxons.
     *
     * @param  \App\Models\User  $user
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
     * @param  \App\Models\User  $user
     * @param  int  $parentId
     * @return bool
     */
    protected function canCreateWithParent(User $user, $parentId)
    {
        if (! $taxon = Taxon::find($parentId)) {
            return false;
        }

        return $taxon->isCuratedBy($user);
    }

    /**
     * Determine whether the user can update the taxon.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Taxon  $taxon
     * @return mixed
     */
    public function update(User $user, Taxon $taxon)
    {
        return $user->hasRole('admin') || $taxon->isCuratedBy($user);
    }

    /**
     * Determine whether the user can delete the taxon.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Taxon  $taxon
     * @return mixed
     */
    public function delete(User $user, Taxon $taxon)
    {
        return $user->hasRole('admin') || $taxon->isCuratedBy($user);
    }
}
