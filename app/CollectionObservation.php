<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CollectionObservation extends Model
{
    /**
     * Main observation data.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function observation()
    {
        return $this->morphOne(Observation::class, 'details');
    }
}
