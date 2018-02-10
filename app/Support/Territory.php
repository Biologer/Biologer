<?php

namespace App\Support;

class Territory
{
    /**
     * Get data on territory used to center Google Maps input.
     * If no territory name is given use configured default.
     *
     * @param  string  $name
     * @return \Illuminate\Support\Collection
     */
    public static function get($name)
    {
        if (is_null($name)) {
            $name = config('biologer.territory');
        }

        return collect([
            'center' => static::center($name),
        ]);
    }

    protected static function center($name)
    {
        $territory = strtolower($name);

        $territories = [];

        foreach (config('biologer.territories', []) as $key => $value) {
            $territories[strtolower($key)] = $value;
        }

        if (! array_key_exists($territory, $territories)) {
            throw new \Exception("Territory with the name of {$name} is not defined.");
        }

        return $territories[$territory];
    }
}
