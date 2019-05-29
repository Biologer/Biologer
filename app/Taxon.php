<?php

namespace App;

use App\Filters\Filterable;
use Dimsav\Translatable\Translatable;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class Taxon extends Model
{
    use Concerns\CanBeCurated,
        Concerns\CanMemoize,
        Concerns\HasAncestry,
        Concerns\HasTranslatableAttributes,
        Filterable,
        Translatable;

    /**
     * @var array
     */
    const RANKS = [
        // 'root' => 100,
        'kingdom' => 70,
        'phylum' => 60,
        'subphylum' => 57,
        // 'superclass' => 53,
        'class' => 50,
        'subclass' => 47,
        // 'superorder' => 43,
        'order' => 40,
        'suborder' => 37,
        'infraorder' => 35,
        'superfamily' => 33,
        // 'epifamily' => 32,
        'family' => 30,
        'subfamily' => 27,
        // 'supertribe' => 26,
        'tribe' => 25,
        'subtribe' => 24,
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
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'allochthonous' => false,
        'invasive' => false,
        'restricted' => false,
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $appends = ['rank_translation', 'native_name', 'description'];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['translations'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'allochthonous' => 'boolean',
        'invasive' => 'boolean',
        'rank_level' => 'integer',
        'restricted' => 'boolean',
    ];

    /**
     * Attributes that are translated.
     *
     * @var array
     */
    public $translatedAttributes = ['native_name', 'description'];

    /**
     * Should translation fallback be used.
     *
     * @var bool
     */
    public $useTranslationFallback = false;

    /**
     * Filters that can be used on queries.
     *
     * @var array
     */
    protected function filters()
    {
        return [
            'id' => \App\Filters\Id::class,
            'name' => \App\Filters\Taxon\NameLike::class,
            'sort_by' => \App\Filters\SortBy::class,
            'except' => \App\Filters\ExceptIds::class,
            'rank' => \App\Filters\Taxon\Rank::class,
            'updated_after' => \App\Filters\Taxon\UpdatedAfter::class,
            'limit' => \App\Filters\Limit::class,
        ];
    }

    /**
     * List of fields that taxa can be sorted by.
     *
     * @return array
     */
    public static function sortableFields()
    {
        return [
            'id', 'name', 'rank_level',
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
    public function conservationLegislations()
    {
        return $this->belongsToMany(
            ConservationLegislation::class,
            'conservation_legislation_taxon',
            'taxon_id',
            'leg_id'
        );
    }

    /**
     * Conservation lists by which the taxon should be protected.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function conservationDocuments()
    {
        return $this->belongsToMany(
            ConservationDocument::class,
            'conservation_document_taxon',
            'taxon_id',
            'doc_id'
        );
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
     * Actovity log.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject')->latest();
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
     * Scope the query to get only species or taxa of higher ranks.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSpeciesOrHigher($query)
    {
        return $query->where('rank_level', '>=', static::RANKS['species']);
    }

    /**
     * Scope the query to get only taxa with scientific or native name like the one given.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $name
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithScientificOrNativeName($query, $name)
    {
        return $query->where('name', 'like', '%'.$name.'%')
            ->orWhereTranslationLike('native_name', '%'.$name.'%');
    }

    /**
     * Scope the query to get only taxa that have ancestors with scientific
     * or native name like the one given.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $name
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrHasAncestorsWithScientificOrNativeName($query, $name)
    {
        return $query->orWhereHas('ancestors', function ($query) use ($name) {
            return $query->withScientificOrNativeName($name);
        });
    }

    /**
     * Scope the query to get only taxa that have ancestor with given ID.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $taxonId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrHasAncestorWithId($query, $taxonId)
    {
        return $query->orWhereHas('ancestors', function ($query) use ($taxonId) {
            return $query->whereId($taxonId);
        });
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
     * Native name translation in current locale.
     *
     * @return string
     */
    public function getNativeNameAttribute()
    {
        return $this->translateOrNew($this->locale())->native_name;
    }

    /**
     * Description translation in current locale.
     *
     * @return string
     */
    public function getDescriptionAttribute()
    {
        return $this->translateOrNew($this->locale())->description;
    }

    /**
     * Check if taxon is a species.
     *
     * @return bool
     */
    public function isSpecies()
    {
        return $this->rank === 'species';
    }

    /**
     * Get complete list of MGRS fields the taxon was observed at.
     *
     * @return array
     */
    public function mgrs10k()
    {
        return $this->memoize('mgrs', function () {
            return Observation::approved()
                ->whereIn('taxon_id', $this->selfAndDescendantsIds())
                ->pluck('mgrs10k');
        });
    }

    /**
     * Get occurrence data (date, elevation and stage) for taxon to present it on the chart.
     *
     * @return \Illuminate\Support\Collection
     */
    public function occurrence()
    {
        return $this->memoize('occurrence', function () {
            return Observation::query()
                ->approved()
                ->whereIn('taxon_id', $this->selfAndDescendantsIds())
                ->withCompleteDate()
                ->with('stage')
                ->select('elevation', 'year', 'month', 'day', 'stage_id')
                ->get()
                ->map(function ($observation) {
                    $month = str_pad($observation->month, 2, '0', STR_PAD_LEFT);
                    $day = str_pad($observation->day, 2, '0', STR_PAD_LEFT);

                    return [
                        'elevation' => $observation->elevation,
                        'date' => $observation->year.'-'.$month.'-'.$day,
                        'stage' => $observation->stage ? $observation->stage->name : 'adult',
                    ];
                });
        });
    }

    /**
     * Get confirmed photos of taxon and its descendants.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function publicPhotos()
    {
        return $this->memoize('publicPhotos', function () {
            return Observation::approved()
                ->whereIn('taxon_id', $this->selfAndDescendantsIds())
                ->with('publicPhotos')
                ->get()
                ->pluck('publicPhotos')
                ->flatten();
        });
    }

    /**
     * Taxon ranks as options for frontend.
     *
     * @return array
     */
    public static function getRankOptions()
    {
        return array_map(function ($rank, $level) {
            return [
                'level' => $level,
                'value' => $rank,
                'label' => trans('taxonomy.'.$rank),
            ];
        }, array_keys(static::RANKS), static::RANKS);
    }

    /**
     * Get species IDs for all ancestors, including them.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $ancestors
     * @param  bool  $onlyObserved
     * @return \Illuminate\Support\Collection
     */
    public static function getSpeciesIdsForAncestors(EloquentCollection $ancestors, $onlyObserved = false)
    {
        return self::where(function ($query) use ($ancestors) {
            return $query->whereHas('ancestors', function ($query) use ($ancestors) {
                return $query->whereIn('id', $ancestors->pluck('id'));
            })->orWhereIn('id', $ancestors->pluck('id'));
        })->species()->orderByAncestry()->when($onlyObserved, function ($query) {
            return $query->has('observations');
        })->pluck('id');
    }

    /**
     * Get IDs of descendants of given taxa and their own IDs.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $ancestors
     * @param  bool  $onlyObserved
     * @return \Illuminate\Support\Collection
     */
    public static function getSelfAndDescendantIdsForAncestors(EloquentCollection $ancestors, $onlyObserved = false)
    {
        return self::where(function ($query) use ($ancestors) {
            return $query->whereHas('ancestors', function ($query) use ($ancestors) {
                return $query->whereIn('id', $ancestors->pluck('id'));
            })->orWhereIn('id', $ancestors->pluck('id'));
        })->orderByAncestry()->when($onlyObserved, function ($query) {
            return $query->has('observations');
        })->pluck('id');
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->activity()->delete();
        });
    }
}
