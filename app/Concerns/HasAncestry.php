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
        return $this->belongsToMany(static::class, $this->getModelNameLower().'_ancestors', 'model_id', 'ancestor_id');
    }

    /**
     * Descendants relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function descendants()
    {
        return $this->belongsToMany(static::class, $this->getModelNameLower().'_ancestors', 'ancestor_id', 'model_id');
    }

    /**
     * Query only species.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSpecies($query)
    {
        return $query->where('rank_level', 10);
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
     * @param  static|integer  $parent
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
        return $this->id === $model->parent_id ;
    }

    /**
     * Ranks
     * @return array
     */
    public static function getRanks()
    {
        return [
            // 100 => 'root',
            70 => 'kingdom',
            60 => 'phylum',
            // 57 => 'subphylum',
            // 53 => 'superclass',
            50 => 'class',
            // 47 => 'subclass',
            // 43 => 'superorder',
            40 => 'order',
            // 37 => 'suborder',
            // 35 => 'infraorder',
            // 33 => 'superfamily',
            // 32 => 'epifamily',
            30 => 'family',
            // 27 => 'subfamily',
            // 26 => 'supertribe',
            // 25 => 'tribe',
            // 24 => 'subtribe',
            20 => 'genus',
            // 20 => 'genushybrid',
            10 => 'species',
            // 10 => 'hybrid',
            5 => 'subspecies',
            // 5 => 'variety',
            // 5 => 'form',
        ];
    }

    /**
     * Build up ordered ancestry list.
     *
     * @return string
     */
    public function generateAncestryBasedOnParentsAncestry()
    {
        if (! $this->parent) {
            return;
        }

        if ($this->parent->isRoot()) {
            return $this->parent->id;
        }

        return $this->parent->ancestry.'/'.$this->parent->id;
    }

    /**
     * The "booting" method of the trait.
     *
     * @return void
     */
    protected static function bootHasAncestry()
    {
        // Cache ancestry.
        static::saving(function ($model) {
            $model->ancestry = $model->generateAncestryBasedOnParentsAncestry();
        });

        // Store links
        static::saved(function ($model) {
            $model->linkAncestors();
        });
    }

    /**
     * Take parent and it's ancestors and link them all as this model's ancestors.
     *
     * @return void
     */
    public function linkAncestors()
    {
        if ($this->isRoot()) {
            return $this->ancestors()->detach();
        }

        $this->ancestors()->sync($this->parent->ancestors);

        $this->ancestors()->attach($this->parent);
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
     * Get self and descending species.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function selfAndDescendingSpecies()
    {
        if ($this->rank_level !== 10) {
            return $this->descendingSpecies;
        }

        return $this->descendingSpecies->prepend($this);
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
                $model->linkAncestors();

                return $model;
            })->load(['ancestors', 'parent'])
            ->each(function ($model) {
                $model->update([
                    'ancestry' => $model->generateAncestryBasedOnParentsAncestry(),
                ]);
            });
    }
}
