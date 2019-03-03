<?php

namespace App\Filters;

use Illuminate\Support\Arr;

class Ids
{
    public function apply($query, $value)
    {
        return $query->whereIn('id', Arr::wrap($value));
    }
}
