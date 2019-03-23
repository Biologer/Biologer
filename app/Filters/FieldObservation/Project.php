<?php

namespace App\Filters\FieldObservation;

class Project
{
    public function apply($query, $value)
    {
        return $query->whereHas('observation', function ($query) use ($value) {
            return $query->where('project', 'like', '%'.$value.'%');
        });
    }
}
