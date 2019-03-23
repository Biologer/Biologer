<?php

namespace App;

use Dimsav\Translatable\Translatable;

class RedList extends Model
{
    use Translatable;

    /**
     * Red List categories.
     *
     * @var array
     */
    const CATEGORIES = ['EX', 'EW', 'CR', 'EN', 'VU', 'NT', 'LC', 'DD', 'NE'];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['translations'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['name'];

    public $translatedAttributes = ['name'];

    /**
     * Taxa on the list.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function taxa()
    {
        return $this->belongsToMany(Taxon::class)->withPivot('category');
    }

    /**
     * Get translated name
     *
     * @return string|null
     */
    public function getNameAttribute()
    {
        return $this->translateOrNew($this->locale())->name;
    }
}
