<?php

namespace App\Filters\Taxon;

use Illuminate\Support\Arr;

class Groups
{
    public function apply($query, $value)
    {
        $value = Arr::wrap($value);

        return $query->where(function ($query) use ($value) {
            $query->whereHas('groups', function ($query) use ($value) {
                $query->whereIn('id', $value)->orWhereIn('parent_id', $value);
            })->orWhereHas('ancestors.groups', function ($query) use ($value) {
                $query->whereIn('id', $value)->orWhereIn('parent_id', $value);
            });
        });
    }
}
