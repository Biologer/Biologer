<?php

namespace App\Exports\FieldObservations;

use App\Export;

class ContributorFieldObservationsCustomExport extends CustomFieldObservationsExport
{
    /**
     * Database query to get the data for export.
     *
     * @param  \App\Export  $export
     * @return \Illuminate\Database\Query\Builder
     */
    protected function query(Export $export)
    {
        return parent::query($export)->createdBy($export->user);
    }
}
