<?php

return [
    'photos_per_observation' => 3,
    'gmaps' => [
        'api_key' => env('GMAP_API_KEY', null),
        'center' => [
            'latitude' => (float) env('MAP_CENTER_LATITUDE', 44.15068115978091),
            'longitude' => (float) env('MAP_CENTER_LONGITUDE', 20.7257080078125),
            'zoom' => (int) env('MAP_CENTER_ZOOM', 7),
        ],
    ],
];
