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
    public static function findByName($name)
    {
        if (is_null($name)) {
            $name = config('biologer.territory');
        }

        return collect(config('biologer.territories', []))->mapWithKeys(function ($value, $key) {
            return [strtolower($key) => $value];
        })->getCollect(strtolower($name), function () {
            throw new \Exception("Territory with the name of {$name} is not defined.");
        });
    }
}
