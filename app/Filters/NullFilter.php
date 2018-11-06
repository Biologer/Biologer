<?php

namespace App\Filters;

class NullFilter
{
    public function apply($query, $value)
    {
        return $query;
    }
}
