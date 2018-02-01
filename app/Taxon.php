<?php

namespace App;

use App\Filters\Filterable;

class Taxon extends Model
{
    use Concerns\CanBeCurated,
        Concerns\HasAncestry,
        Filterable;

    /**
     * @var array
     */
    const RANKS = [
        // 'root' => 100,
        'kingdom' => 70,
        'phylum' => 60,
        // 'subphylum' => 57,
        // 'superclass' => 53,
        'class' => 50,
        // 'subclass' => 47,
        // 'superorder' => 43,
        'order' => 40,
        // 'suborder' => 37,
        // 'infraorder' => 35,
        // 'superfamily' => 33,
        // 'epifamily' => 32,
        'family' => 30,
        // 'subfamily' => 27,
        // 'supertribe' => 26,
        // 'tribe' => 25,
        // 'subtribe' => 24,
        'genus' => 20,
        // 'genushybrid' => 20,
        'species' => 10,
        'speciescomplex' => 10,
        // 'hybrid' => 10,
        'subspecies' => 5,
        // 'variety' => 5,
        // 'form' => 5,
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'taxa';

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $appends = ['rank_translation'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'restricted' => 'boolean',
        'rank_level' => 'integer',
    ];

    /**
     * Filters that can be used on queries.
     *
     * @var array
     */
    protected function filters()
    {
        return [
            'id' => \App\Filters\Id::class,
            'name' => \App\Filters\NameLike::class,
            'sort_by' => \App\Filters\SortBy::class,
            'except' => \App\Filters\ExceptIds::class,
            'rank' => \App\Filters\Taxon\Rank::class,
        ];
    }

    /**
     * Find taxon by name.
     *
     * @param  string  $name
     * @return \App\Taxon
     */
    public static function findByName($name)
    {
        return static::where('name', $name)->first();
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
     * Conservation lists by which the taxon should be protected.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function conservationLists()
    {
        return $this->belongsToMany(ConservationList::class);
    }

    /**
     * Life cycle stages the taxon goes through.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function stages()
    {
        return $this->belongsToMany(Stage::class);
    }

    /**
     * Scope the query to get only species or taxa of lower ranks.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSpeciesOrLower($query)
    {
        return $query->where('rank_level', '<=', static::RANKS['species']);
    }

    /**
     * When setting rank, set it's level as well.
     *
     * @param string  $value
     */
    public function setRankAttribute($value)
    {
        $this->attributes['rank'] = $value;
        $this->attributes['rank_level'] = static::RANKS[$value];
    }

    /**
     * Rank translation.
     *
     * @return string
     */
    public function getRankTranslationAttribute()
    {
        return trans('taxonomy.'.$this->rank);
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
        return array_map(function ($rank) {
            return [
                'value' => $rank,
                'label' => trans('taxonomy.'.$rank),
            ];
        }, array_keys(static::RANKS));
    }
}
