<?php

namespace App\ActivityLog;

class TimedCountObservationDiff
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
            'start_time',
            'end_time',
            'count_duration',
            'cloud_cover',
            'atmospheric_pressure',
            'humidity',
            'temperature',
            'wind_direction',
            'wind_speed',
            'habitat',
            'area',
            'route_length',
            'geometry',
            'latitude',
            'longitude',
            'observer',
            'observed_by_id',
            'view_groups_id',
        ];
    }

    /**
     * If we want to display the value of changed attribute differently,
     * we define a function extract it here.
     *
     * @return array
     */
    protected static function valueOverrides()
    {
        return [
            'start_time' => function ($timedCountObservation) {
                return $timedCountObservation->start_time ? $timedCountObservation->start_time->toIso8601String() : null;
            },
            'end_time' => function ($timedCountObservation) {
                return $timedCountObservation->end_time ? $timedCountObservation->end_time->toIso8601String() : null;
            },
            'count_duration' => function ($timedCountObservation) {
                return $timedCountObservation->count_duration ? $timedCountObservation->count_duration.' minutes' : null;
            },
            'cloud_cover' => function ($timedCountObservation) {
                return $timedCountObservation->cloud_cover ? $timedCountObservation->cloud_cover.'%' : null;
            },
            'humidity' => function ($timedCountObservation) {
                return $timedCountObservation->humidity ? $timedCountObservation->humidity.'%' : null;
            },
            'temperature' => function ($timedCountObservation) {
                return $timedCountObservation->temperature ? $timedCountObservation->temperature.' °C' : null;
            },
            'wind_speed' => function ($timedCountObservation) {
                return $timedCountObservation->wind_speed ? $timedCountObservation->wind_speed.' km/h' : null;
            },
            'atmospheric_pressure' => function ($timedCountObservation) {
                return $timedCountObservation->atmospheric_pressure ? $timedCountObservation->atmospheric_pressure.' hPa' : null;
            },
        ];
    }
}
