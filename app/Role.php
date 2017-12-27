<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Find role by it's name.
     *
     * @param  string  $name
     * @return self
     */
    public static function findByName($name)
    {
        return static::where('name', $name)->first();
    }
}
