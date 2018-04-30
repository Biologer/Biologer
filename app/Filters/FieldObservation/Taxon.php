<?php

namespace App\Filters\FieldObservation;

class Taxon
{
    public function apply($query, $value)
    {
        return $query->whereHas('observation', function ($query) use ($value) {
            return $query->whereHas('taxon', function ($query) use ($value) {
                return $query->where('name', 'like', '%'.$value.'%')
                    ->orWhereTranslationLike('native_name', '%'.$value.'%');
            });
        });
    }
}
