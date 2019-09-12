<?php

namespace App\Filters;

class SortBy
{
    public function apply($query, $value, $param, $filterData, $model)
    {
        $sort = explode('.', $value);
        $field = $sort[0] ?? 'id';
        $order = isset($sort[1]) && 'desc' === $sort[1] ? 'desc' : 'asc';

        $sortableFields = method_exists($model, 'sortableFields') && is_callable($model, 'sortableFields')
            ? $model::sortableFields()
            : [];

        if (! in_array($field, $sortableFields)) {
            return $query;
        }

        if (method_exists($model, 'scopeOrderByMapped')) {
            return $query->orderByMapped($field, $order);
        }

        return $query->orderBy($field, $order);
    }
}
