<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Image Generator
    |--------------------------------------------------------------------------
    |
    | Here you can specify driver used to generate CAPTCHA image. Available
    | drivers are: "gd" and "imagick".
    |
    */

    'driver' => 'gd',

    /*
    |--------------------------------------------------------------------------
    | Image properties
    |--------------------------------------------------------------------------
    |
    | Here you can specify properties of generated image. Width and height
    | must be declared in pixels.
    |
    */

    'image' => [
        'width' => 240,

        'height' => 100,

        'padding' => 5,

        'background_color' => 0xFFFFFF,

        'text_color' => 0x2040A0,

        'offset' => -2,
    ],

    /*
    |--------------------------------------------------------------------------
    | Verification Code Length
    |--------------------------------------------------------------------------
    |
    | Here you can specify minimum and maximum value of verification code.
    | The value must be 3 and 20.
    |
    */

    'min_length' => 6,

    'max_length' => 7,

    /*
    |--------------------------------------------------------------------------
    | CAPTCHA Validation Case Sensitivity
    |--------------------------------------------------------------------------
    |
    | Here you can specify if validation of the code should be case sensitive.
    |
    */

    'case_sensitive' => false,

    /*
    |--------------------------------------------------------------------------
    | CAPTCHA Validation Allowed Failures
    |--------------------------------------------------------------------------
    |
    | Here you can specify how many failed validation attempts are allowed
    | before new code is generated. If set to 0, new CAPTCHA code won't
    | be generated on failure.
    |
    */

    'allowed_failures' => 1,

    /*
    |--------------------------------------------------------------------------
    | Route
    |--------------------------------------------------------------------------
    |
    | Here you can specify route that will be used for fetching CAPTCHA image.
    |
    */

    'route' => '_captcha',

    /*
    |--------------------------------------------------------------------------
    | Refresh Query Param Name
    |--------------------------------------------------------------------------
    |
    | Here you can specify name of the query parameter that marks that
    | CAPTCHA code should be regenerated.
    |
    */

    'refresh_query_param' => 'refresh',

    /*
    |--------------------------------------------------------------------------
    | Session Key
    |--------------------------------------------------------------------------
    |
    | Here you can specify session key to be used for storing verification code.
    |
    */

    'session_key' => '__captcha',
];
