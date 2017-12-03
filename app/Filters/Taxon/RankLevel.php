<?php

namespace App\Filters\Taxon;

class RankLevel
{
    public function apply($query, $value)
    {
        return $query->where('rank_level', $value);
    }
}
