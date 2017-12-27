<?php

namespace App;

use App\Filters\Filterable;

class Taxon extends Model
{
    use Concerns\HasAncestry, Filterable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'taxa';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['rank', 'conventions_ids', 'red_lists_data'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'restricted' => 'boolean',
    ];

    /**
     * Filters that can be used on queries.
     *
     * @var array
     */
    protected function filters() {
        return [
            'id' => \App\Filters\Id::class,
            'name' => \App\Filters\NameLike::class,
            'sort_by' => \App\Filters\SortBy::class,
            'except' => \App\Filters\ExceptId::class,
            'rank_level' => \App\Filters\Taxon\RankLevel::class,
        ];
    }

    /**
     * Observations relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function observations()
    {
        return $this->hasMany(Observation::class);
    }

    /**
     * Approved observations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function approvedObservations()
    {
        return $this->observations()->approved();
    }

    /**
     * Unapproved observations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function unapprovedObservations()
    {
        return $this->observations()->unapproved();
    }

    /**
     * Red lists the taxon is on.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function redLists()
    {
        return $this->belongsToMany(RedList::class)->withPivot('category');
    }

    /**
     * Conventions by which the taxon is should be protected.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function conventions()
    {
        return $this->belongsToMany(Convention::class);
    }

    /**
     * Direct curators for the taxon.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function curators()
    {
        return $this->belongsToMany(User::class);
    }

    public function scopeCuratedBy($query, User $user)
    {
        return $query->whereHas('curators', function ($q) use ($user) {
            return $q->where('id', $user->id);
        })->orWhereHas('ancestors.curators', function ($q) use ($user) {
            return $q->where('id', $user->id);
        });
    }

    /**
     * Get taxonomic rank name.
     *
     * @return string
     */
    public function getRankAttribute()
    {
        $ranks = static::getRanks();

        return trans('taxonomy.'.$ranks[$this->rank_level]);
    }

    /**
     * Get IDs of coventions that cover the taxon.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getConventionsIdsAttribute()
    {
        return $this->conventions->pluck('id');
    }

    public function getRedListsDataAttribute()
    {
        return $this->redLists->map(function ($redList) {
            return [
                'red_list_id' => $redList->id,
                'category' => $redList->pivot->category,
            ];
        });
    }

    /**
     * Get list of MGRS fields the taxon was observed at.
     *
     * @return array
     */
    public function mgrs()
    {
        return $this->approvedObservations()
            ->pluck('mgrs10k')
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Taxon ranks as options for frontend.
     *
     * @return array
     */
    public static function getRankOptions()
    {
        return array_map(function ($rank, $index) {
            return [
                'value' => $index,
                'name' => trans('taxonomy.'.$rank),
            ];
        }, static::getRanks(), array_keys(static::getRanks()));
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
        return $this->curators->contains(function ($curator) use ($user) {
            return $curator->is($user);
        });
    }

    /**
     * Check if given user is curator for taxon's ancestor.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function ancestorIsCuratedBy(User $user)
    {
        return $this->ancestors->load('curators')->contains(function ($ancestor) use ($user) {
            return $ancestor->isDirectlyCuratedBy($user);
        });
    }
}
