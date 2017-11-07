<?php

namespace App\Filters;

class NameLike
{
    public function apply($query, $value)
    {
        return $query->where('name', 'like', $value.'%');
    }
}
