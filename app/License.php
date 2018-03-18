<?php

namespace App;

class License
{
    const CC_BY_SA = 10;
    const CC_BY_NC_SA = 20;
    const PARTIALLY_OPEN = 30;
    const CLOSED = 40;

    public static function all()
    {
        return collect([
            [
                'id' => static::CC_BY_SA,
                'name' => 'CC BY-SA 4.0',
            ],
            [
                'id' => static::CC_BY_NC_SA,
                'name' => 'CC BY-NC-SA 4.0',
            ],
            [
                'id' => static::PARTIALLY_OPEN,
                'name' => 'Partially open',
            ],
            [
                'id' => static::CLOSED,
                'name' => 'Closed',
            ],
        ]);
    }

    /**
     * List of available licenses.
     *
     * @return array
     */
    public static function getOptions()
    {
        return static::all()->mapWithKeys(function ($license) {
            return [$license['id'] => __("licenses.{$license['name']}")];
        })->all();
    }

    /**
     * Get license ids.
     *
     * @return array
     */
    public static function ids()
    {
        return static::all()->pluck('id');
    }

    public static function firstId()
    {
        return static::ids()->first();
    }

    public static function first()
    {
        return static::all()->first();
    }

    /**
     * Get license name.
     *
     * @param  int  $id
     * @return string|null
     */
    public static function findById($id)
    {
        return static::all()->where('id', $id)->first();
    }

    public static function findByName($name)
    {
        return static::all()->where('name', $name)->first();
    }
}
