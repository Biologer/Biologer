<?php

use App\Support\Mgrs;

function mgrs10k($latitude, $longitude)
{
    return Mgrs::makeFromLatLong($latitude, $longitude)->to10k();
}
