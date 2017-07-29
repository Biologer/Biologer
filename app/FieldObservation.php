<?php

namespace App;

class FieldObservation extends Model
{
    public function observation()
    {
        return $this->morphOne(Observation::class, 'details');
    }

    public function comments()
    {
        return $this->observation->comments();
    }
}
