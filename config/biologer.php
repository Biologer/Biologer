<?php

return [
    'photos_per_observation' => 3,

    'photo_resize_dimension' => (int) env('PHOTO_RESIZE_DIMENSION', null),

    'territory' => env('MAP_TERRITORY', 'Serbia'),

    'territories' => [
        'Serbia' => [
            'center' => [
                'latitude' => 44.15068115978091,
                'longitude' => 20.7257080078125,
                'zoom' => 7,
            ],
        ],

        'Croatia' => [
            'center' => [
                'latitude' => 45.657371,
                'longitude' => 16.377937,
                'zoom' => 7,
            ],
        ],
    ],
];
