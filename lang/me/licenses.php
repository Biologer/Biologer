<?php

return [
    'data' => [
        \App\License::CC_BY_SA => 'Otvorena (CC BY-SA 4.0)',
        \App\License::CC_BY_NC_SA => 'Otvorena, nekomercijalna (CC BY-NC-SA 4.0)',
        \App\License::PARTIALLY_OPEN => 'Djelimično otvorena',
        \App\License::TEMPORARILY_CLOSED => 'Zatvorena na 3 godine',
        \App\License::CLOSED => 'Zatvorena',
    ],
    'image' => [
        \App\ImageLicense::CC_BY_SA => 'Otvorena (CC BY-SA 4.0)',
        \App\ImageLicense::CC_BY_NC_SA => 'Otvorena, nekomercijalna (CC BY-NC-SA 4.0)',
        \App\ImageLicense::PARTIALLY_OPEN => 'Zadržana prava autora',
        \App\ImageLicense::CLOSED => 'Zatvorena',
    ],
];
