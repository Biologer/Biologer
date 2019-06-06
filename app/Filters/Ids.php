<?php

namespace App\Filters;

use Illuminate\Support\Arr;

class Ids
{
    public function apply($query, $value, $param, $filterData, $modelClass)
    {
        $table = (new $modelClass)->getTable();

        return $query->whereIn("{$table}.id", Arr::wrap($value));
    }
}
