<?php

namespace App;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Collection;

class FieldObservationCollection extends Collection
{
    /**
     * Get ids of Observation models connected to FieldObservation.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getObservationIds()
    {
        return $this->pluck('observation.id');
    }

    /**
     * Get ids of FieldObservation models.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getIds()
    {
        return $this->pluck('id');
    }

    /**
     * Approve all field observations.
     *
     * @return void
     */
    public function approve()
    {
        $now = Carbon::now();

        Observation::query()->whereIn('id', $this->getObservationIds())->update([
            'approved_at' => $now,
            'updated_at' => $now,
        ]);

        FieldObservation::query()->whereIn('id', $this->getIds())->update([
            'unidentifiable' => false,
            'updated_at' => $now,
        ]);
    }

    /**
     * Mark all field observations as unidentifiable.
     *
     * @return void
     */
    public function markAsUnidentifiable()
    {
        $now = Carbon::now();

        Observation::query()->whereIn('id', $this->getObservationIds())->update([
            'approved_at' => null,
            'updated_at' => $now,
        ]);

        FieldObservation::query()->whereIn('id', $this->getIds())->update([
            'unidentifiable' => true,
            'updated_at' => $now,
        ]);
    }
}
