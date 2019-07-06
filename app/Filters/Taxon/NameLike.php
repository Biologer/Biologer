<?php

namespace App\Filters\Taxon;

class NameLike
{
    public function apply($query, $value, $param, $requestData)
    {
        return $query->where(function ($query) use ($value, $requestData) {
            $includeChildren = filter_var($requestData->get('includeChildTaxa', false), FILTER_VALIDATE_BOOLEAN);

            if (! empty($taxonId = $requestData->get('taxonId'))) {
                return $this->whereId($query, $taxonId, $includeChildren);
            }

            return $this->whereName($query, $value, $includeChildren);
        });
    }

    private function whereId($query, $taxonId, $includeChildren)
    {
        $query->whereId($taxonId);

        if ($includeChildren) {
            $query->orHasAncestorWithId($taxonId);
        }

        return $query;
    }

    private function whereName($query, $name, $includeChildren)
    {
        $query->withScientificOrNativeName($name);

        if ($includeChildren) {
            $query->orHasAncestorsWithScientificOrNativeName($name);
        }

        return $query;
    }
}
