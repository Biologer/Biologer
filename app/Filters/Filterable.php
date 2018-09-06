<?php

namespace App\Filters;

trait Filterable
{
    protected function filters()
    {
        return [];
    }

    public function scopeFilter($query, $request, array $filters = [])
    {
        $filters = $filters ?: $this->filters();

        foreach ($request->all() as $param => $value) {
            $shouldFilter = array_key_exists($param, $filters)
                && $value && class_exists($filters[$param]);

            if ($shouldFilter) {
                $query = (new $filters[$param]())->apply($query, $value, $param, $request);
            }
        }

        return $query;
    }
}
