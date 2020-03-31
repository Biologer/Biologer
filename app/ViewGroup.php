<?php

namespace App;

use App\Concerns\CanMemoize;
use App\Concerns\HasTranslatableAttributes;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ViewGroup extends Model
{
    use CanMemoize, HasTranslatableAttributes, Translatable;

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
     * Taxa in this group.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function taxa()
    {
        return $this->belongsToMany(Taxon::class);
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
            return Taxon::speciesInGroup($this)->pluck('id');
        });
    }

    /**
     * Get all taxa that are species or higher and have given name.
     *
     * @param  string|null  $name
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function allTaxaHigherOrEqualSpeciesRank($name = null)
    {
        return Taxon::inGroup($this)
            ->speciesOrHigher()
            ->orderByAncestry()
            ->with(['descendants' => function ($query) {
                $query->when($this->only_observed_taxa, function ($query) {
                    $query->where(function ($query) {
                        $query->has('observations')->orHas('descendants.observations');
                    });
                });
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
        return Taxon::speciesInGroup($this)->orderByAncestry()->paginate($perPage);
    }

    /**
     * Get the species paginator for given species id.
     *
     * @param  int  $speciesId
     * @return \App\Taxon|null
     */
    public function findSpecies($speciesId)
    {
        $species = Taxon::speciesInGroup($this)->with('ancestors')->findOrFail($speciesId);

        return new SpeciesGroupPaginator($this, $species);
    }

    /**
     * Relation to first species, which we get through subquery select.
     *
     * @return \App\Taxon|null
     */
    public function firstSpecies()
    {
        return $this->memoize(__FUNCTION__, function () {
            return Taxon::speciesInGroup($this)->withCount('observations')->orderByAncestry()->first();
        });
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

        return Storage::disk('public')->url($path);
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
}
