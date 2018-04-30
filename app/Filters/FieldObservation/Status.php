<?php

namespace App\Filters\FieldObservation;

use App\FieldObservation;

class Status
{
    public function apply($query, $value)
    {
        if (FieldObservation::STATUS_UNIDENTIFIABLE === $value) {
            return $query->unidentifiable();
        }

        if (FieldObservation::STATUS_APPROVED === $value) {
            return $query->approved();
        }

        if (FieldObservation::STATUS_PENDING === $value) {
            return $query->pending();
        }
    }
}
