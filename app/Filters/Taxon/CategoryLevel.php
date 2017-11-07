<?php

namespace App\Filters\Taxon;

class CategoryLevel
{
    public function apply($query, $value)
    {
        return $query->where('category_level', $value);
    }
}
