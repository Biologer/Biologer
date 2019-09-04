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
            $this->ancestorsPivotTableName(),
            'model_id',
            'ancestor_id'
        )->orderBy('rank_level', 'desc');
    }

    /**
     * Get name of ancestors pivot table.
     *
     * @return string
     */
    private function ancestorsPivotTableName()
    {
        return $this->getModelNameLower().'_ancestors';
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
            $this->ancestorsPivotTableName(),
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
     * Sort the query by the name of each ancestor in the ancestry tree.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByAncestry($query)
    {
        $subquery = static::query()
            // We can't expect ancestors to be sorted how we want them before we concatenate
            // their names, and while MySQL supports `ORDER BY` inside `GROUP_CONCAT`,
            // SQLite doesn't. That's why we use derived table that is already
            // sorted properly (by rank level, descending).
            ->fromSub(static::query()->orderBy('rank_level', 'desc'), 'ancestors')
            ->selectRaw('GROUP_CONCAT(`ancestors`.`name`)')
            ->whereIn('ancestors.id', function ($query) {
                $query->select('ancestor_id')
                    ->from($this->ancestorsPivotTableName())
                    ->whereColumn('model_id', $this->getQualifiedKeyName());
            });

        return $query->select(['*'])
            ->selectSub($subquery, 'ancestors_names')
            ->orderBy('ancestors_names')
            ->orderBy('name');
    }

    /**
     * Scope the query to get only root taxa.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
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

        $ancestors = $this->parent->ancestors->concat([$this->parent]);

        $this->ancestors()->sync($ancestors);

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
        foreach (static::RANKS as $rankLevel) {
            static::with(['parent.ancestors'])
                ->where('rank_level', $rankLevel)
                ->each(function ($taxon) {
                    $taxon->linkAncestors();
            });
        }
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
            ->with(['parent.ancestors'])
            ->orderBy('rank_level', 'desc')
            ->get()
            ->each->linkAncestors();

        return $this;
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
            $model->linkAncestors();
        });

        static::updating(function ($model) {
            if ($model->isDirty('parent_id')) {
                $model->load('parent')->rebuildAncestryDown();
            }
        });
    }
}
