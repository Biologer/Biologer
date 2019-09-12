<?php

namespace App\Http\Controllers\Api\My;

use App\Exports\FieldObservations\ContributorFieldObservationsCustomExport;
use App\Exports\FieldObservations\ContributorFieldObservationsExport;
use App\Http\Controllers\Api\FieldObservationExportsController as BaseController;

class FieldObservationExportsController extends BaseController
{
    public function __construct(ContributorFieldObservationsExport $fieldObservationsExport)
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
        return ContributorFieldObservationsCustomExport::availableColumns();
    }
}
