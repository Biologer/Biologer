<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    public function getNameTranslationAttribute()
    {
        return trans('stages.'.$this->name);
    }

    /**
     * Find the stage by name.
     *
     * @param  string  $name
     * @return self
     */
    public static function findByName($name)
    {
        return static::where('name', $name)->first();
    }
}
