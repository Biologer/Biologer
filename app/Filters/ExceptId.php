<?php

namespace App\Filters;

class ExceptId
{
    public function apply($query, $value)
    {
        return $query->where('id', '<>', $value);
    }
}
