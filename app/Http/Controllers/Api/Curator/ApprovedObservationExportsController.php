<?php

namespace App\Http\Controllers\Api\Curator;

use App\Exports\FieldObservations\CuratorApprovedFieldObservationsCustomExport;
use App\Exports\FieldObservations\CuratorApprovedFieldObservationsExport;
use App\Http\Controllers\Api\FieldObservationExportsController;

class ApprovedObservationExportsController extends FieldObservationExportsController
{
    public function __construct(CuratorApprovedFieldObservationsExport $fieldObservationsExport)
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
        return CuratorApprovedFieldObservationsCustomExport::availableColumns();
    }
}
