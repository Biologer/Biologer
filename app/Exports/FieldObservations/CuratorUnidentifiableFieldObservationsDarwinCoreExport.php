<?php

namespace App\Exports\FieldObservations;

use App\Export;

class CuratorUnidentifiableFieldObservationsDarwinCoreExport extends DarwinCoreFieldObservationsExport
{
    /**
     * Database query to get the data for export.
     *
     * @param  \App\Export  $export
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function query(Export $export)
    {
        return parent::query($export)->curatedBy($export->user)->unidentifiable();
    }
}
