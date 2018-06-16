<?php

namespace App\Filters;

class Ids
{
    public function apply($query, $value)
    {
        return $query->whereIn('id', array_wrap($value));
    }
}
