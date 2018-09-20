<?php

namespace App\Filters\FieldObservation;

class Taxon
{
    public function apply($query, $value, $param, $request)
    {
        return $query->whereHas('observation', function ($query) use ($value, $request) {
            $includeChildren = filter_var($request->input('includeChildTaxa', false), FILTER_VALIDATE_BOOLEAN);

            return $query->hasTaxonWithScientificOrNativeName($value, $includeChildren);
        });
    }
}
