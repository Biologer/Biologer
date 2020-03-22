<?php

namespace App\Filters;

use Illuminate\Http\Request;

trait Filterable
{
    /**
     * Filter list.
     *
     * @return array
     */
    protected function filters()
    {
        return [];
    }

    /**
     * Apply filters to query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $data
     * @param  array  $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, $data, array $filters = [])
    {
        $filters = $filters ?: $this->filters();
        $filterData = $this->getFilterData($data);

        foreach ($filterData as $param => $value) {
            $shouldFilter = array_key_exists($param, $filters) && $value && class_exists($filters[$param]);

            if ($shouldFilter) {
                $query = (new $filters[$param]())->apply($query, $value, $param, $filterData, static::class);
            }
        }

        return $query;
    }

    /**
     * Retrieve data to filter by.
     *
     * @param  mixed  $data
     * @return \Illuminate\Support\Collection
     */
    private function getFilterData($data)
    {
        if ($data instanceof Request) {
            return collect($data->all());
        }

        return collect($data);
    }
}
