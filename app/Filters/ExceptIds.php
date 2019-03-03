<?php

namespace App\Filters;

use Illuminate\Support\Arr;

class ExceptIds
{
    public function apply($query, $values)
    {
        return $query->whereNotIn('id', Arr::wrap($values));
    }
}
