<?php

namespace App\Filters\Taxon;

class NameLike
{
    public function apply($query, $value)
    {
        return $query->where(function ($query) use ($value) {
            $query->where('name', 'like', '%'.$value.'%')
                ->orWhereTranslationLike('native_name', '%'.$value.'%');
        });
    }
}
