<?php

namespace App\Http\Controllers\Api\Curator;

use App\Http\Controllers\Api\FieldObservationExportsController;
use App\Exports\FieldObservations\CuratorApprovedFieldObservationsExport;
use App\Exports\FieldObservations\CuratorApprovedFieldObservationsCustomExport;

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
