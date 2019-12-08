<?php

namespace App\Filters\LiteratureObservation;

class ObservationAttributeFilter
{
    public function apply($query, $value, $param)
    {
        if (empty($value)) {
            return $query;
        }

        return $query->whereHas('observation', function ($query) use ($param, $value) {
            $query->where($param, $value);
        });
    }
}
