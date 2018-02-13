<?php

namespace App\Filters;

class ExceptIds
{
    public function apply($query, $values)
    {
        return $query->whereNotIn('id', array_wrap($values));
    }
}
