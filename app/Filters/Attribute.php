<?php

namespace App\Filters;

class Attribute
{
    public function apply($query, $value, $param)
    {
        if (! empty($value)) {
            return $query->where($param, $value);
        }
    }
}
