<?php

namespace App;

use App\Concerns\CanMemoize;
use App\Concerns\HasTranslatableAttributes;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ViewGroup extends Model
{
    use HasFactory, CanMemoize, HasTranslatableAttributes, Translatable;

    const CACHE_GROUPS_WITH_FIRST_SPECIES = 'groups_with_first_species';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'parent_id' => 'integer',
        'sort_order' => 'integer',
        'only_observed_taxa' => 'boolean',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $appends = ['name', 'description'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = ['image_path'];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['translations'];

    /**
     * Attributes that are translated.
     *
     * @var array
     */
    public $translatedAttributes = ['name', 'description'];

    /**
     * The event map for the model.
     *
     * Allows for object-based events for native Eloquent events.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'saved' => \App\Events\ViewGroupSaved::class,
        'deleted' => \App\Events\ViewGroupDeleted::class,
    ];

    /**
     * Query only main groups. We'll use these for tabs.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Add observations count to the subquery.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithObservationsCount($query)
    {
        $subquery = Observation::query()
            ->selectRaw('count(*)')
            ->join('taxon_ancestors', 'taxon_ancestors.model_id', '=', 'observations.taxon_id')
            ->join('taxon_view_group', 'taxon_view_group.taxon_id', '=', 'taxon_ancestors.ancestor_id')
            ->whereColumn('taxon_view_group.view_group_id', 'view_groups.id');

        return $query->selectSub($subquery, 'observations_count');
    }

    /**
     * Child groups. We'll use these to fill tabed sections.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function groups()
    {
        return $this->hasMany(static::class, 'parent_id');
    }

    /**
     * Taxa connected to group.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function taxa()
    {
        return $this->belongsToMany(Taxon::class);
    }

    /**
     * All taxa in this group, directly connected to group or descendants of those directly connected.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function allTaxa()
    {
        return $this->belongsToMany(Taxon::class, 'view_group_taxa');
    }

    /**
     * Species in the group.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function species()
    {
        return $this->allTaxa()->species();
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
     * Name translation in current locale.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->translateOrNew($this->locale())->name;
    }

    /**
     * Name translation in current locale with fallback to configured
     * fallback locale if there is no name in current locale.
     *
     * @return string
     */
    public function getNameWithFallbackAttribute()
    {
        return $this->translateOrNew($this->locale())->name
            ?? $this->translateOrNew($this->getFallbackLocale())->name;
    }

    /**
     * Check if group is root.
     *
     * @return bool
     */
    public function isRoot()
    {
        return empty($this->parent_id);
    }

    /**
     * IDs of species related to the group through connected taxa.
     *
     * @return \Illuminate\Support\Collection
     */
    public function speciesIds()
    {
        return $this->memoize('speciesIds', function () {
            return $this->species()->when($this->only_observed_taxa, function ($query) {
                $query->leftJoin('taxon_ancestors', 'taxon_ancestors.ancestor_id', '=', 'taxa.id')
                    ->where(function ($query) {
                        $query->addWhereExistsQuery(Observation::whereColumn('taxon_id', 'taxa.id')->getQuery())
                            ->addWhereExistsQuery(Observation::whereColumn('taxon_id', 'taxon_ancestors.model_id')->getQuery(), 'or');
                    });
            })->selectRaw('DISTINCT(id) as id')->pluck('id');
        });
    }

    /**
     * Get all taxa that are species or higher and have given name.
     *
     * @param  string|null  $name
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function allTaxaHigherOrEqualSubspeciesRank($name = null)
    {
        return $this->allTaxa()
            ->when($this->only_observed_taxa, function ($query) {
                $query->observed();
            })
            ->subspeciesOrHigher()
            ->orderByAncestry()
            ->with(['descendants' => function ($query) {
                $query->when($this->only_observed_taxa, function ($query) {
                    $query->observed();
                })->orderByAncestry();
            }])->when($name, function ($query, $name) {
                $query->withScientificOrNativeName($name);
            });
    }

    /**
     * Get paginated list of species inside the group.
     *
     * @param  int  $perPage
     * @return \Illuminate\Pagination\Paginator
     */
    public function paginatedSpeciesList($perPage = 30)
    {
        return $this->species()->when($this->only_observed_taxa, function ($query) {
            $query->observed();
        })->orderByAncestry()->paginate($perPage);
    }

    /**
     * Get the species paginator for given species id.
     *
     * @param  int  $speciesId
     * @return \App\Taxon|null
     */
    public function findSpecies($speciesId)
    {
        $species = $this->species()
            ->when($this->only_observed_taxa, function ($query) {
                $query->observed();
            })
            ->with('ancestors')
            ->findOrFail($speciesId);

        return new SpeciesGroupPaginator($this, $species);
    }

    /**
     * Relation to first species, which we get through subquery select.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function firstSpecies()
    {
        return $this->belongsTo(Taxon::class);
    }

    /**
     * Eager load first species for group.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopeWithFirstSpecies($query)
    {
        $query->selectFirstSpeciesId()->with('firstSpecies');
    }

    /**
     * Add select for first species using subquery.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopeSelectFirstSpeciesId($query)
    {
        $firstSpeciesIdQuery = Taxon::select('id')
            ->join('view_group_taxa', 'view_group_taxa.taxon_id', '=', 'taxa.id')
            ->whereColumn('view_group_taxa.view_group_id', 'view_groups.id')
            ->where(function ($query) {
                $query->where('view_groups.only_observed_taxa', false)->orWhere->observed();
            })
            ->species()->orderByAncestry()->limit(1);

        $query->addSelect(['first_species_id' => $firstSpeciesIdQuery]);
    }

    /**
     * Save the image to the disk.
     *
     * @param  string  $originalName
     * @param  string|resource  $image
     * @return string
     */
    public static function saveImageToDisk($originalName, $image)
    {
        $path = 'groups/'.Str::random().'-'.$originalName;

        Storage::disk('public')->put($path, $image);

        return $path;
    }

    protected function deleteImage($path)
    {
        Storage::disk('public')->delete($path);
    }

    /**
     * Get URL of image that should be used as default when the group has no image chosen.
     *
     * @return string
     */
    public static function defaultImage()
    {
        return asset('img/default-image.svg');
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->image_url = $model->image_path
                ? Storage::disk('public')->url($model->image_path)
                : null;
        });

        static::updated(function ($model) {
            if ($model->wasChanged('image_path') && $path = $model->getOriginal('image_path')) {
                $model->deleteImage($path);
            }
        });

        static::deleted(function ($model) {
            $model->deleteImage($model->image_path);
        });
    }
}
