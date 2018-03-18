<?php

namespace App;

use Dimsav\Translatable\Translatable;

class ObservationType extends Model
{
    use Translatable;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

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

    /**
     * The attributes that should be visible in serialization.
     *
     * @var array
     */
    protected $visible = ['id', 'slug', 'name'];

    /**
     * Translated attributes.
     *
     * @var array
     */
    public $translatedAttributes = ['name'];

    /**
     * Accessor for name attribute.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->translateOrNew($this->locale())->name;
    }
}
