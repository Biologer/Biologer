<?php

namespace App;

class ViewGroup extends Model
{
    /**
     * Query only main categories. We'll use these for tabs.
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

    public function species()
    {
        // TODO: Implement
    }
}
