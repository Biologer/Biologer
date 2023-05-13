<?php

namespace App;

use App\Filters\Filterable;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

class Taxon extends Model
{
    use HasFactory,
        Concerns\CanBeCurated,
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
        'subkingdom' => 67,
        'infrakingdom' => 65,
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
        'parent_id' => 'integer',
        'allochthonous' => 'boolean',
        'invasive' => 'boolean',
        'rank_level' => 'integer',
        'elevation' => 'integer',
        'restricted' => 'boolean',
        'uses_atlas_codes' => 'boolean',
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
     * The event map for the model.
     *
     * Allows for object-based events for native Eloquent events.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => \App\Events\TaxonCreated::class,
        'deleted' => \App\Events\TaxonDeleted::class,
    ];

    /**
     * Filters that can be used on queries.
     *
     * @var array
     */
    public function filters()
    {
        return [
            'id' => \App\Filters\Ids::class,
            'name' => \App\Filters\Taxon\NameLike::class,
            'sort_by' => \App\Filters\SortBy::class,
            'except' => \App\Filters\ExceptIds::class,
            'rank' => \App\Filters\Taxon\Rank::class,
            'updated_after' => \App\Filters\UpdatedAfter::class,
            'limit' => \App\Filters\Limit::class,
            'taxonId' => \App\Filters\NullFilter::class,
            'includeChildTaxa' => \App\Filters\NullFilter::class,
            'groups' => \App\Filters\Taxon\Groups::class,
            'ungrouped' => \App\Filters\Taxon\Ungrouped::class,
            'synonyms' => \App\Filters\NullFilter::class,
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
            'id', 'name', 'rank_level', 'author', 'synonyms',
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
     * Find taxon by rank, name and ancestors.
     *
     * @param string $name
     * @param string $rank,
     * @param string|null $ancestor
     * @return \App\Taxon
     */
    public static function findByRankNameAndAncestor(string $name, string $rank, ?string $ancestor = null)
    {
        $build = static::where(['name' => $name, 'rank' => $rank]);

        if ($build->count() > 1) {
            return $build->where('ancestors_names', 'like', $ancestor)->first();
        }

        return $build->first();
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
     * Groups that the taxon is part of directly.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(ViewGroup::class);
    }

    /**
     * Synonyms for the taxon.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function synonyms()
    {
        return $this->hasMany(Synonym::class);
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
        return $query->where(function ($query) use ($name) {
            $query->where('name', 'like', '%'.$name.'%')
                ->orWhereTranslationLike('native_name', '%'.$name.'%')
                ->orWhereHas('synonyms', function ($query) use ($name) {
                    $query->where('name', 'like', '%'.$name.'%');
                });
        });
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
     * Scope the query to get taxa in given group.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\ViewGroup  $group
     * @return void
     */
    public function scopeInGroup($query, ViewGroup $group)
    {
        $query->where(function ($query) use ($group) {
            $query->whereHas('groups', function ($query) use ($group) {
                $query->where('id', $group->id);
            })->orWhereHas('ancestors.groups', function ($query) use ($group) {
                $query->where('id', $group->id);
            });
        })->when($group->only_observed_taxa, function ($query) {
            $query->observed();
        });
    }

    /**
     * Get only taxa that have been observed, either directly or their descendent.
     */
    public function scopeObserved($query)
    {
        $query->where(function ($query) {
            $query->has('observations')->orHas('descendants.observations');
        });
    }

    /**
     * Scope the query to get species in given group.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\ViewGroup  $group
     * @return void
     */
    public function scopeSpeciesInGroup($query, ViewGroup $group)
    {
        $query->inGroup($group)->species();
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
     * Check if taxon is a species.
     *
     * @return bool
     */
    public function isSpeciesLike()
    {
        return $this->rank_level === self::RANKS['species'];
    }

    /**
     * Check if taxon is a species or lower.
     *
     * @return bool
     */
    public function isSpeciesOrLower()
    {
        return $this->rank_level <= static::RANKS['species'];
    }

    /**
     * Get complete list of MGRS fields the taxon was observed at.
     *
     * @return array
     */
    public function mgrs10k()
    {
        return $this->memoize('mgrs', function () {
            $result = Observation::approved()
                ->ofTaxa($this->selfAndDescendantsIds())
                ->where(function ($query) {
                    $query->where('details_type', '!=', (new FieldObservation)->getMorphClass())
                        ->orWhereHasMorph('details', [FieldObservation::class], function ($query) {
                            $query->public();
                        });
                })
                ->getQuery()
                ->groupBy('mgrs10k', 'details_type')
                ->get([
                    'mgrs10k as field',
                    'details_type as type',
                    DB::raw('COUNT(*) as observations_count'),
                ]);

            $presentInLiterature = $result->where('type', LiteratureObservation::class)->pluck('field');

            return $result->groupBy('field')->map(function ($fieldData, $field) use ($presentInLiterature) {
                return [
                    'observations_count' => $fieldData->sum('observations_count'),
                    'present_in_literature' => $presentInLiterature->contains($field),
                ];
            })->sortKeys();
        });
    }

    /**
     * Get occurrence data (date, elevation and stage) for taxon to present it on the chart.
     *
     * @return \Illuminate\Support\Collection
     */
    public function occurrence()
    {
        return $this->memoize(__FUNCTION__, function () {
            return Observation::approved()
                ->ofTaxa($this->selfAndDescendantsIds())
                ->where(function ($query) {
                    // Exclude those that are found dead
                    $query->where('details_type', '!=', (new FieldObservation)->getMorphClass())
                        ->orWhereHasMorph('details', [FieldObservation::class], function ($query) {
                            $query->where('found_dead', false)->public();
                        });
                })
                ->withCompleteDate()
                ->whereNotNull('elevation')
                ->whereDoesntHave('types', function ($query) {
                    $query->where('slug', 'exuviae');
                })
                ->leftJoin('stages', 'stages.id', '=', 'observations.stage_id')
                ->select('observations.id', 'observations.elevation', 'observations.year', 'observations.month', 'observations.day', 'stages.name as stage_name')
                ->getQuery()
                ->get()
                ->map(function ($observation) {
                    return $this->mapOccurrence($observation);
                });
        });
    }

    protected function mapOccurrence($observation)
    {
        $month = str_pad($observation->month, 2, '0', STR_PAD_LEFT);
        $day = str_pad($observation->day, 2, '0', STR_PAD_LEFT);

        return [
            'elevation' => (int) $observation->elevation,
            'date' => $observation->year.'-'.$month.'-'.$day,
            'stage' => $observation->stage_name ?? 'unknown',
        ];
    }

    /**
     * Get confirmed photos of taxon and its descendants.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function publicPhotos()
    {
        return $this->memoize(__FUNCTION__, function () {
            return Photo::public()->whereHas('observations', function ($query) {
                $query->approved()->ofTaxa($this->selfAndDescendantsIds());
            })->get();
        });
    }

    /**
     * Check if taxon is of rank genus or lower.
     *
     * @return bool
     */
    public function isGenusOrLower()
    {
        return $this->rank_level <= static::RANKS['genus'];
    }

    /**
     * Get descendants of next lower rank.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function lowerRankDescendants()
    {
        if ($rank = $this->lowerRank()) {
            return $this->descendants()->where('rank', $rank)->get();
        }

        return EloquentCollection::make();
    }

    /**
     * Get next lower rank.
     *
     * @return string|null
     */
    private function lowerRank()
    {
        $take = false;
        $previousLevel = null;

        foreach (static::RANKS as $rank => $level) {
            if ($previousLevel === $level) {
                continue;
            }

            if ($take) {
                return $rank;
            }

            $previousLevel = $level;

            if ($this->rank === $rank) {
                $take = true;
            }
        }
    }

    /**
     * Taxon ranks as options for frontend.
     *
     * @return array
     */
    public static function getRankOptions()
    {
        return collect(static::RANKS)->map(function ($level, $rank) {
            return [
                'level' => $level,
                'value' => $rank,
                'label' => trans('taxonomy.'.$rank),
            ];
        })->values();
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
            $model->synonyms()->delete();
        });
    }
}
