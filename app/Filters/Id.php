<?php

namespace App\Filters;

class Id
{
    public function apply($query, $value)
    {
        return $query->where('id', $value);
    }
}
