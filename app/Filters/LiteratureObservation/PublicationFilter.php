<?php

namespace App\Filters\LiteratureObservation;

class PublicationFilter
{
    public function apply($query, $value)
    {
        return $query->where('publication_id', $value);
    }
}
