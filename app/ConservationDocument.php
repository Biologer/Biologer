<?php

namespace App;

use Astrotomic\Translatable\Translatable;

class ConservationDocument extends Model
{
    use Translatable;

    protected $translationForeignKey = 'doc_id';

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
    protected $appends = ['name', 'description'];

    /**
     * Attributes that can be translated.
     *
     * @var array
     */
    public $translatedAttributes = ['name', 'description'];

    /**
     * Get translated conservation document name.
     *
     * @return string|null
     */
    public function getNameAttribute()
    {
        return $this->translateOrNew($this->locale())->name;
    }

    /**
     * Get translated conservation document description.
     *
     * @return string|null
     */
    public function getDescriptionAttribute()
    {
        return $this->translateOrNew($this->locale())->description;
    }
}
