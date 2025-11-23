<?php

namespace App\Models;

use App\Filters\Filterable;
use Astrotomic\Translatable\Translatable;

class ObservationType extends Model
{
    use Filterable, Translatable;

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
     * Filter list.
     *
     * @return array
     */
    public function filters()
    {
        return [
            'updated_after' => \App\Filters\UpdatedAfter::class,
        ];
    }

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
