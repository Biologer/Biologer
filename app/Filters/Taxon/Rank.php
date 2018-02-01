<?php

namespace App\Filters\Taxon;

class Rank
{
    public function apply($query, $value)
    {
        return $query->where('rank', $value);
    }
}
