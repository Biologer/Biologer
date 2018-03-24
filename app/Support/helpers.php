<?php

use App\Support\Mgrs;
use App\Support\Territory;

function mgrs10k($latitude, $longitude)
{
    return Mgrs::makeFromLatLong($latitude, $longitude)->to10k();
}

/**
 * Get data on territory used to center Google Maps input.
 * If no territory name is given use configured default.
 *
 * @param  string  $name
 * @return \Illuminate\Support\Collection
 */
function territory($name = null)
{
    return Territory::findByName($name);
}

if (! function_exists('array_key_rename')) {
    function array_key_rename(&$array, $oldKey, $newKey)
    {
        $array[$newKey] = $array[$oldKey];
        unset($array[$oldKey]);
    }
}
