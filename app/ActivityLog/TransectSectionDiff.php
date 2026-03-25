<?php

namespace App\ActivityLog;

class TransectSectionDiff
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
            'length',
            'primary_habitat',
            'secondary_habitat',
            'land_tenure',
            'land_management',
            'transect_observation_count_id',
        ];
    }
}
