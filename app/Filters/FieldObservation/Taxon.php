<?php

namespace App\Filters\FieldObservation;

class Taxon
{
    public function apply($query, $value, $param, $requestData)
    {
        return $query->whereHas('observation', function ($query) use ($value, $requestData) {
            $includeChildren = filter_var($requestData->get('includeChildTaxa', false), FILTER_VALIDATE_BOOLEAN);

            $taxonId = $requestData->get('taxonId');

            if (! empty($taxonId)) {
                return $query->forTaxonWithId($taxonId, $includeChildren);
            }

            return $query->forTaxonWithScientificOrNativeName($value, $includeChildren);
        });
    }
}
