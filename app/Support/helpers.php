<?php

use App\Support\Mgrs;
use App\Support\Territory;
use App\Support\Localization;

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
    return Territory::get($name);
}

function localizationStrings()
{
    return Localization::strings();
}
