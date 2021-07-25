<?php

namespace App\Filters\Taxon;

class Ungrouped
{
    public function apply($query, $value, $param, $requestData)
    {
        $includeUngrouped = filter_var($value, FILTER_VALIDATE_BOOLEAN);

        if ($requestData->has('groups') || ! $includeUngrouped) {
            return $query;
        }

        return $query->where(function ($query) {
            $query->doesntHave('groups')->doesntHave('ancestors.groups');
        });
    }
}
