<?php

namespace App\Policies;

use App\Photo;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PhotoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the photo.
     *
     * @param  \App\User  $user
     * @param  \App\Photo  $photo
     * @return mixed
     */
    public function view(User $user, Photo $photo)
    {
        //
    }

    /**
     * Determine whether the user can create photos.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the photo.
     *
     * @param  \App\User  $user
     * @param  \App\Photo  $photo
     * @return mixed
     */
    public function update(User $user, Photo $photo)
    {
        //
    }

    /**
     * Determine whether the user can delete the photo.
     *
     * @param  \App\User  $user
     * @param  \App\Photo  $photo
     * @return mixed
     */
    public function delete(User $user, Photo $photo)
    {
        //
    }

    /**
     * Owner or curator in charge of taxon in the photo.
     *
     * @param  \App\User  $user
     * @param  \App\Photo  $photo
     * @return bool
     */
    public function viewOriginal(User $user, Photo $photo)
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($photo->observations()->createdBy($user)->exists()) {
            return true;
        }

        if ($user->hasRole('curator') && $photo->observations()->taxonCuratedBy($user)->exists()) {
            return true;
        }

        return false;
    }
}
