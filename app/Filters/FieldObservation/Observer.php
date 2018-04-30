<?php

namespace App\Filters\FieldObservation;

class Observer
{
    public function apply($query, $value)
    {
        return $query->where('observer', 'like', '%'.$value.'%');
    }
}
