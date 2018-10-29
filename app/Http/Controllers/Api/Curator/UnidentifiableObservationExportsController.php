<?php

namespace App\Http\Controllers\Api\Curator;

use App\Http\Controllers\Api\FieldObservationExportsController;
use App\Exports\FieldObservations\CuratorUnidentifiableFieldObservationsExport;
use App\Exports\FieldObservations\CuratorUnidentifiableFieldObservationsCustomExport;

class UnidentifiableObservationExportsController extends FieldObservationExportsController
{
    public function __construct(CuratorUnidentifiableFieldObservationsExport $fieldObservationsExport)
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
        return CuratorUnidentifiableFieldObservationsCustomExport::availableColumns();
    }
}
