<?php

namespace App\Filters\Publication;

class Search
{
    public function apply($query, $value)
    {
        return $query->where('citation', 'like', "%{$value}%");
    }
}
