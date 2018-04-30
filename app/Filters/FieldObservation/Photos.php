<?php

namespace App\Filters\FieldObservation;

use App\FieldObservation;

class Photos
{
    public function apply($query, $value)
    {
        if (! is_string($value)) {
            return;
        }

        if ('yes' === strtolower($value)) {
            return $query->whereHas('observation', function ($query) {
                return $query->has('photos');
            });
        }

        if ('no' === strtolower($value)) {
            return $query->whereHas('observation', function ($query) {
                return $query->doesntHave('photos');
            });
        }
    }
}
