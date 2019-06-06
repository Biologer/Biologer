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
        $transformed = $this->transformItem($item);

        if ($item->license()->shouldHideRealCoordinates()) {
            $transformed['latitude'] = __('N/A');
            $transformed['longitude'] = __('N/A');
        }

        return $transformed;
    }
}
