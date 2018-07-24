<?php

namespace App\Exports;

use App\Export;

class CuratorPendingFieldObservationsExport extends FieldObservationsExport
{
    /**
     * Database query to get the data for export.
     *
     * @param  \App\Export  $export
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function query(Export $export)
    {
        return parent::query($export)->curatedBy($export->user)->pending();
    }
}
