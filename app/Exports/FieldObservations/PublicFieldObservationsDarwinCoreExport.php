<?php

namespace App\Exports\FieldObservations;

use App\Export;

class PublicFieldObservationsDarwinCoreExport extends DarwinCoreFieldObservationsExport
{
    /**
     * Database query to get the data for export.
     *
     * @param  \App\Export  $export
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function query(Export $export)
    {
        return parent::query($export)->public();
    }

    /**
     * Extract needed data from item.
     *
     * @param  \App\FieldObservation  $item
     * @return array
     */
    protected function transformItem($item)
    {
        $transformed = parent::transformItem($item);

        if ($item->shouldHideRealCoordinates()) {
            $transformed['decimalLatitude'] = number_format($item->observation->latitude, 1);
            $transformed['decimalLongitude'] = number_format($item->observation->longitude, 1);
            $transformed['coordinateUncertaintyInMeters'] = 5000;
        }

        if ($item->license()->shouldntShowExactDate()) {
            $transformed['month'] = null;
            $transformed['day'] = null;
        }

        return $transformed;
    }
}
