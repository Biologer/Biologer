<?php

namespace App\Filters\LiteratureObservation;

class TaxonFilter
{
    public function apply($query, $value, $param, $requestData)
    {
        return $query->whereHas('observation', function ($query) use ($value, $requestData) {
            $includeChildren = filter_var($requestData->get('include_child_taxa', false), FILTER_VALIDATE_BOOLEAN);

            $taxonId = $requestData->get('taxon_id');

            if (! empty($taxonId)) {
                return $query->forTaxonWithId($taxonId, $includeChildren);
            }

            return $query->forTaxonWithScientificOrNativeName($value, $includeChildren);
        });
    }
}
