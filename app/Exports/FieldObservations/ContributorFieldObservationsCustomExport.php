<?php

namespace App\Exports\FieldObservations;

use App\Models\Export;

class ContributorFieldObservationsCustomExport extends CustomFieldObservationsExport
{
    /**
     * Database query to get the data for export.
     *
     * @param  \App\Models\Export  $export
     * @return \Illuminate\Database\Query\Builder
     */
    protected function query(Export $export)
    {
        return parent::query($export)->createdBy($export->user);
    }
}
