<?php

namespace App\Filters\Taxon;

use Illuminate\Support\Arr;

class Groups
{
    public function apply($query, $value, $param, $requestData)
    {
        $value = Arr::wrap($value);

        $includeUngrouped = filter_var($requestData->get('ungrouped', false), FILTER_VALIDATE_BOOLEAN);

        return $query->where(function ($query) use ($value, $includeUngrouped) {
            $query->whereHas('groups', function ($query) use ($value) {
                $query->whereIn('id', $value);
            })->orWhereHas('ancestors.groups', function ($query) use ($value) {
                $query->whereIn('id', $value);
            });

            if ($includeUngrouped) {
                $query->orWHere(function ($query) {
                    $query->doesntHave('groups')->doesntHave('ancestors.groups');
                });
            }
        });
    }
}
