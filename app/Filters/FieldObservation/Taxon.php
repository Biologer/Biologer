<?php

namespace App\Filters\FieldObservation;

class Taxon
{
    public function apply($query, $value, $param, $request)
    {
        return $query->whereHas('observation', function ($query) use ($value, $request) {
            $includeChildren = filter_var($request->input('includeChildTaxa', false), FILTER_VALIDATE_BOOLEAN);

            $taxonId = $request->input('taxonId');

            if (! empty($taxonId)) {
                return $query->forTaxonWithId($taxonId, $includeChildren);
            }

            return $query->forTaxonWithScientificOrNativeName($value, $includeChildren);
        });
    }
}
