<?php

namespace App\DEM;

interface Reader
{
    /**
     * Get elevation in meters for given coordinates.
     *
     * @param  float  $latitude
     * @param  float  $longitude
     * @return int|null
     */
    public function getElevation($latitude, $longitude);
}
