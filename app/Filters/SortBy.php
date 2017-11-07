<?php

namespace App\Filters;

class SortBy
{
    public function apply($query, $value)
    {
        $sort = explode('.', $value);
        $field = $sort[0] ?? 'id';
        $order = isset($sort[1]) && $sort[1] === 'desc' ? 'desc' : 'asc';

        return $query->orderBy($field, $order);
    }
}
