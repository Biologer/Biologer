<?php

namespace App;

use App\Concerns\CanMemoize;
use Dimsav\Translatable\Translatable;
use App\Concerns\HasTranslatableAttributes;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ViewGroup extends Model
{
    use CanMemoize, HasTranslatableAttributes, Translatable;

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
            return $this->taxa->map->selfAndDescendingSpeciesIds()->flatten();
        });
    }

    /**
     * Get IDs of all taxa related to the group through connected taxa.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function allTaxaIds()
    {
        return $this->memoize('allTaxaIds', function () {
            return $this->taxa->map->selfAndDescendantsIds()->flatten();
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
        $query = Taxon::whereIn('id', $this->allTaxaIds())
            ->speciesOrHigher()
            ->orderByAncestry()
            ->with('descendants');

        if (! empty($name)) {
            $query->where(function ($query) use ($name) {
                return $query->where('name', 'like', "%{$name}%")
                    ->orWhereTranslationLike('native_name', "%{$name}%");
            });
        }

        return $query;
    }

    /**
     * Get all species that are part of the group.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function species()
    {
        return $this->memoize('species', function () {
            return Taxon::with('ancestors')->whereIn('id', $this->speciesIds())->get();
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
        return Taxon::whereIn('id', $this->speciesIds())->paginate($perPage);
    }

    /**
     * Find species that is inside the group or throw exception.
     *
     * @param  int|string  $speciesId
     * @return \App\Taxon
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail($speciesId)
    {
        if (! $this->speciesIds()->contains($speciesId)) {
            throw new ModelNotFoundException("Species with the ID of {$speciesId} is not in this group.");
        }

        return $this->findSpecies($speciesId);
    }

    /**
     * Get the species paginator for given species id.
     *
     * @param  int  $speciesId
     * @return \App\Taxon|null
     */
    protected function findSpecies($speciesId)
    {
        $species = Taxon::with('ancestors')->species()->where('id', $speciesId)->first();

        if (! $species) {
            return;
        }

        return new SpeciesGroupPaginator($this, $species);
    }

    /**
     * Get first species in the group.
     *
     * @return \App\Taxon|null
     */
    public function firstSpecies()
    {
        if (! ($id = $this->speciesIds()->first())) {
            return optional();
        }

        return $this->findSpecies($id);
    }
}
