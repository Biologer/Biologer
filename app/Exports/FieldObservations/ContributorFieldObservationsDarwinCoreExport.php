<?php

namespace App\Exports\FieldObservations;

use App\Models\Export;

class ContributorFieldObservationsDarwinCoreExport extends DarwinCoreFieldObservationsExport
{
    /**
     * Database query to get the data for export.
     *
     * @param  \App\Models\Export  $export
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    protected function query(Export $export)
    {
        return parent::query($export)->createdBy($export->user);
    }
}
