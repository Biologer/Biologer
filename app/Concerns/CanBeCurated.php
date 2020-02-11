<?php

namespace App\Concerns;

use App\User;

trait CanBeCurated
{
    /**
     * Direct curators for the taxon.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function curators()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Get only taxa that can be curated by the given user.
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\User  $user
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCuratedBy($query, User $user)
    {
        return $query->where(function ($q) use ($user) {
            $q->whereHas('curators', function ($q) use ($user) {
                $q->where('id', $user->id);
            })->orWhereHas('ancestors.curators', function ($q) use ($user) {
                $q->where('id', $user->id);
            });
        });
    }

    /**
     * Check if given user is curator for the taxon.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function isCuratedBy(User $user)
    {
        return $this->isDirectlyCuratedBy($user) || $this->ancestorIsCuratedBy($user);
    }

    /**
     * Check if given user is direct curator for the taxon.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function isDirectlyCuratedBy(User $user)
    {
        return $this->curators->contains->is($user);
    }

    /**
     * Check if given user is curator for taxon's ancestor.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function ancestorIsCuratedBy(User $user)
    {
        return $this->ancestors->loadMissing('curators')->contains->isDirectlyCuratedBy($user);
    }

    /**
     * Add curator for this taxon.
     *
     * @param  \App\User  $user
     * @return self
     */
    public function addCurator(User $user)
    {
        $this->curators()->attach($user);

        return $this;
    }

    /**
     * Check if taxon can be approved by given user.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function canBeApprovedBy(User $user)
    {
        return $this->isSpeciesOrLower() && $this->isCuratedBy($user);
    }
}
