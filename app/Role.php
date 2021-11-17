<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

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

    /**
     * Find all roles that have given names.
     *
     * @param  array|string  $names
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function findAllByName($names)
    {
        return static::whereIn('name', $names)->get();
    }
}
