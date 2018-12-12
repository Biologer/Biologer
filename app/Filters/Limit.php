<?php

namespace App\Filters;

class Limit
{
    public function apply($query, $value)
    {
        return $query->take($value);
    }
}
