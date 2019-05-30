<?php

namespace App\Filters;

class AttributeLike
{
    public function apply($query, $value, $param)
    {
        if (! empty($value)) {
            return $query->where($param, 'like', "%{$value}%");
        }
    }
}
