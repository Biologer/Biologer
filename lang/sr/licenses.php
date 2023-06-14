<?php

return [
    'data' => [
        \App\License::CC_BY_SA => 'Отворена (CC BY-SA 4.0)',
        \App\License::CC_BY_NC_SA => 'Отворена, некомерцијална (CC BY-NC-SA 4.0)',
        \App\License::PARTIALLY_OPEN => 'Делимично отворена',
        \App\License::TEMPORARILY_CLOSED => 'Затворена на 3 године',
        \App\License::CLOSED => 'Затворена',
    ],
    'image' => [
        \App\ImageLicense::CC_BY_SA => 'Отворена (CC BY-SA 4.0)',
        \App\ImageLicense::CC_BY_NC_SA => 'Отворена, некомерцијална (CC BY-NC-SA 4.0)',
        \App\ImageLicense::PARTIALLY_OPEN => 'Задржана права аутора',
        \App\ImageLicense::CLOSED => 'Затворена',
    ],
];
