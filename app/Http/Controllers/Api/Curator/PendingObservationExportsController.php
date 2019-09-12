<?php

namespace App\Http\Controllers\Api\Curator;

use App\Exports\FieldObservations\CuratorPendingFieldObservationsCustomExport;
use App\Exports\FieldObservations\CuratorPendingFieldObservationsExport;
use App\Http\Controllers\Api\FieldObservationExportsController;

class PendingObservationExportsController extends FieldObservationExportsController
{
    public function __construct(CuratorPendingFieldObservationsExport $fieldObservationsExport)
    {
        parent::__construct($fieldObservationsExport);
    }

    /**
     * Columns that can be exported.
     *
     * @return string
     */
    protected function columns()
    {
        return CuratorPendingFieldObservationsCustomExport::availableColumns();
    }
}
