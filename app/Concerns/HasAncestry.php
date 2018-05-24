<?php

namespace App\Concerns;

/**
 * Provides ancestry related functionality to the model.
 */
trait HasAncestry
{
    /**
     * Parent relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_id');
    }

    /**
     * Children relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(static::class, 'parent_id');
    }

    /**
     * Ancestors relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function ancestors()
    {
        return $this->belongsToMany(
            static::class,
            $this->getModelNameLower().'_ancestors',
            'model_id',
            'ancestor_id'
        )->orderBy('rank_level', 'desc');
    }

    /**
     * Descendants relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function descendants()
    {
        return $this->belongsToMany(
            static::class,
            $this->getModelNameLower().'_ancestors',
            'ancestor_id',
            'model_id'
        )->orderByAncestry();
    }

    /**
     * Descendants of rank species.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function descendingSpecies()
    {
        return $this->descendants()->species();
    }

    /**
     * Query only species.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSpecies($query)
    {
        return $query->where('rank_level', static::RANKS['species']);
    }

    /**
     * Scope the query to get only species or taxa of lower ranks.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByAncestry($query)
    {
        return $query->orderBy('ancestry')->orderBy('name');
    }

    /**
     * Lowercased model name.
     *
     * @return string
     */
    protected function getModelNameLower()
    {
        return strtolower(class_basename(static::class));
    }

    /**
     * Check if taxon is root.
     *
     * @return bool
     */
    public function isRoot()
    {
        return is_null($this->parent_id);
    }

    /**
     * Check if given taxon is child of this taxon.
     *
     * @param  static|int  $parent
     * @return bool
     */
    public function isChildOf($parent)
    {
        if (is_int($parent)) {
            return $this->parent_id === $parent;
        }

        return $this->parent_id === $parent->id;
    }

    /**
     * Check if given taxon is parent of this taxon.
     *
     * @param  static  $model
     * @return bool
     */
    public function isParentOf($model)
    {
        return $this->id === $model->parent_id;
    }

    /**
     * Take parent and it's ancestors and link them all as this model's ancestors.
     *
     * @return $this
     */
    public function linkAncestors()
    {
        if ($this->isRoot()) {
            $this->ancestors()->detach();

            return $this;
        }

        $this->ancestors()->sync($this->parent->ancestors);

        $this->ancestors()->attach($this->parent);

        return $this;
    }

    /**
     * Get self and descending species.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function selfAndDescendingSpecies()
    {
        if ($this->rank !== 'species') {
            return $this->descendingSpecies;
        }

        return $this->descendingSpecies->prepend($this);
    }

    public function selfAndDescendingSpeciesIds()
    {
        $ids = $this->descendingSpecies()->pluck('id');

        if ($this->rank !== 'species') {
            return $ids;
        }

        return $ids->prepend($this->id);
    }

    public function selfAndDescendantsIds()
    {
        return $this->descendants()->pluck('id')->prepend($this->id);
    }

    /**
     * Rebuild ancestry of entire tree.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function rebuildAncestry()
    {
        return static::with(['ancestors', 'parent'])
            ->orderBy('rank_level', 'desc')
            ->get()
            ->map(function ($model) {
                return $model->linkAncestors();
            });
    }


    /**
     * Rebuild ancestry of entire tree.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function rebuildAncestryDown()
    {
        $this->linkAncestors();

        $this->descendants()
            ->with(['ancestors', 'parent'])
            ->orderBy('rank_level', 'desc')
            ->get()
            ->each->linkAncestors();

        return $this;
    }

    /**
     * Rebuild ancestry cache based on connections to ancestors.
     *
     * @return void
     */
    public static function rebuildAncestryCache()
    {
        static::with('ancestors')
            ->get()
            ->each->cacheAncestry();
    }

    /**
     * Cache ancestry path; used to sort by ancestry.
     *
     * @return void
     */
    public function cacheAncestry()
    {
        $this->update(['ancestry' => $this->generateAncestryCache()]);
    }

    /**
     * Rebuild ancestry cache from this species down to it's last descendant.
     *
     * @return $this
     */
    public function rebuildAncestryCacheDown()
    {
        $this->descendants()
            ->with(['ancestors', 'parent'])
            ->orderBy('rank_level', 'desc')
            ->get()
            ->each->cacheAncestry();

        return $this;
    }

    /**
     * Generate ancestry path cache based on connections to ancestors.
     *
     * @return string
     */
    protected function generateAncestryCache()
    {
        return $this->ancestors->sortByDesc('rank_level')->pluck('id')->push($this->id)->implode('/');
    }

    /**
     * The "booting" method of the trait.
     *
     * @return void
     */
    protected static function bootHasAncestry()
    {
        // Store links
        static::created(function ($model) {
            $model->linkAncestors()->cacheAncestry();
        });

        static::updating(function ($model) {
            if ($model->isDirty('parent_id')) {
                $model->load('parent')->rebuildAncestryDown()->rebuildAncestryCacheDown();

                $model->ancestry = $model->generateAncestryCache();
            }
        });
    }
}
