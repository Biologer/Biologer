<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

abstract class Model extends Eloquent
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Convenience method for getting new instances of the model.
     *
     * @param  array  $attributes
     * @return static
     */
    public static function make(array $attributes = [])
    {
        return new static($attributes);
    }
}
