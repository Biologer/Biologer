<?php

namespace App\Filters\Taxon;

class Group
{
    public function apply($query, $value)
    {
        return $query->where(function ($query) use ($value) {
            $query->whereHas('groups', function ($query) use ($value) {
                $query->where('id', $value)->orWhere('parent_id', $value);
            })->orWhereHas('ancestors.groups', function ($query) use ($value) {
                $query->where('id', $value)->orWhere('parent_id', $value);
            });
        });
    }
}
