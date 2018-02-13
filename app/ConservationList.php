<?php

namespace App;

use Dimsav\Translatable\Translatable;
use App\Concerns\HasTranslatableAttributes;

class ConservationList extends Model
{
    use Translatable, HasTranslatableAttributes;

    protected $translationForeignKey = 'list_id';

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

    /**
     * Taxa that is listed.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function taxa()
    {
        return $this->belongsToMany(Taxon::class);
    }

    public function getNameAttribute()
    {
        return $this->translateOrNew($this->locale())->name;
    }

    public function getDescriptionAttribute()
    {
        return $this->translateOrNew($this->locale())->description;
    }
}
