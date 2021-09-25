<?php

namespace App;

use App\Concerns\HasTranslatableAttributes;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ConservationLegislation extends Model
{
    use HasFactory, Translatable, HasTranslatableAttributes;

    protected $translationForeignKey = 'leg_id';

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
