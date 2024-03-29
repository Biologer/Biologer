<?php

namespace App\Concerns;

use App\TaxonUser;
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
     * Connection to direct curators for the taxon.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function curatorConnections()
    {
        return $this->hasMany(TaxonUser::class);
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
            $q->whereHas('curatorConnections', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->orWhereHas('ancestors.curatorConnections', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->orWhereHas('descendants.curatorConnections', function ($q) use ($user) {
                $q->where('user_id', $user->id);
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
        return $this->isDirectlyCuratedBy($user)
            || $this->ancestorIsCuratedBy($user)
            || $this->descendantIsCuratedBy($user);
    }

    /**
     * Check if given user is direct curator for the taxon.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function isDirectlyCuratedBy(User $user)
    {
        return $this->curatorConnections()->where('user_id', $user->id)->exists();
    }

    /**
     * Check if given user is curator for taxon's ancestor.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function ancestorIsCuratedBy(User $user)
    {
        return $this->ancestors()->whereHas('curatorConnections', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->exists();
    }

    /**
     * Check if given user is curator for taxon's ancestor.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function descendantIsCuratedBy(User $user)
    {
        return $this->descendants()->whereHas('curatorConnections', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->exists();
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
