<?php

namespace App\ActivityLog;

class TransectCountObservationDiff
{
    use MakesDiffs;

    /**
     * List of attributes for which we want to keep track of changes.
     *
     * @return array
     */
    protected static function attributesToDiff()
    {
        return [
            'name',
            'description',
            'location',
            'length',
            'primary_habitat',
        ];
    }
}
