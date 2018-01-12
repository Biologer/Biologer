<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;

class FieldObservationCollection extends Collection
{
    /**
     * Approve all field observations.
     *
     * @return void
     */
    public function approve()
    {
        $this->each(function ($fieldObservation) {
            $fieldObservation->approve();
        });
    }
}