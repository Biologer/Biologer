<?php

return [
    'photos_per_observation' => 3,

    'photo_resize_dimension' => (int) env('PHOTO_RESIZE_DIMENSION', 800),

    'territory' => env('MAP_TERRITORY', 'Serbia'),

    'territories' => [
        'Serbia' => [
            'latitude' => 44.15068115978091,
            'longitude' => 20.7257080078125,
            'zoom' => 7,
        ],
    ],
];
