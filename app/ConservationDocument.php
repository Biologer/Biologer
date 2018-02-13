<?php

namespace App;

use Dimsav\Translatable\Translatable;

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

    public $translatedAttributes = ['name', 'description'];

    public function getNameAttribute()
    {
        return $this->translateOrNew($this->locale())->name;
    }

    public function getDescriptionAttribute()
    {
        return $this->translateOrNew($this->locale())->description;
    }
}
