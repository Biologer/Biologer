<?php

namespace App\Concerns;

trait HasAncestry
{
    /**
     * Parent relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Children relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Ancestors relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function ancestors()
    {
        return $this->belongsToMany(self::class, $this->getModelNameLower().'_ancestors', 'model_id', 'ancestor_id');
    }

    /**
     * Descendants relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function descendants()
    {
        return $this->belongsToMany(self::class, $this->getModelNameLower().'_ancestors', 'ancestor_id', 'model_id');
    }

    /**
     * Lowercased model name.
     *
     * @return string
     */
    protected function getModelNameLower()
    {
        return strtolower(class_basename(self::class));
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
     * @param  self|integer  $parent
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
     * @param  self  $model
     * @return bool
     */
    public function isParentOf($model)
    {
        return $this->id === $model->parent_id ;
    }

    /**
     * The "booting" method of the trait.
     *
     * @return void
     */
    protected static function bootHasAncestry()
    {
        static::created(function ($model) {
            if ($model->isRoot()) {
                return;
            }

            $model->ancestors()->attach($model->parent);

            $model->ancestors()->attach($model->parent->ancestors);
        });
    }
}
