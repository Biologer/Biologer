<?php

namespace App\Filters\FieldObservation;

class Taxon
{
    public function apply($query, $value, $param, $request)
    {
        return $query->whereHas('observation', function ($query) use ($value, $request) {
            return $query->hasTaxonWithScientificOrNativeName($value, $request->input('includeChildTaxa', false));
        });
    }
}
