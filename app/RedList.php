<?php

namespace App;

class RedList extends Model
{
    /**
     * Red List categories.
     * 
     * @var array
     */
    const CATEGORIES = ['EX', 'EW', 'CR', 'EN', 'VU', 'NT', 'LC', 'DD', 'NE'];

    /**
     * Taxa on the list.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function taxa()
    {
        return $this->belongsToMany(Taxon::class)->withPivot('category');
    }
}
