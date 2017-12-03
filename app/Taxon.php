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
    protected $appends = ['rank'];

    /**
     * Filters that can be used on queries.
     *
     * @var array
     */
    protected function filters() {
        return [
            'rank_level' => \App\Filters\Taxon\RankLevel::class,
            'except' => \App\Filters\ExceptId::class,
            'id' => \App\Filters\Id::class,
            'name' => \App\Filters\NameLike::class,
            'sort_by' => \App\Filters\SortBy::class,
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

    public function getRankAttribute()
    {
        $ranks = static::getRanks();

        return trans('taxonomy.'.$ranks[$this->rank_level]);
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
}
