<?php

return [
    'data' => [
        \App\License::CC_BY_SA => 'Open (CC BY-SA 4.0)',
        \App\License::CC_BY_NC_SA => 'Open, non-commercial (CC BY-NC-SA 4.0)',
        \App\License::PARTIALLY_OPEN => 'Partially open',
        \App\License::TEMPORARILY_CLOSED => 'Closed for 3 years',
        \App\License::CLOSED => 'Closed',
    ],
    'image' => [
        \App\ImageLicense::CC_BY_SA => 'Open (CC BY-SA 4.0)',
        \App\ImageLicense::CC_BY_NC_SA => 'Open, non-commercial (CC BY-NC-SA 4.0)',
        \App\ImageLicense::PARTIALLY_OPEN => 'Copyrighted',
        \App\ImageLicense::CLOSED => 'Closed',
    ],
];
