<?php

return [
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
    | before new code is generated. If set to 0, code won't be
    | regenerated on failure.
    |
    */
    'allowed_failures' => 1,

    /*
    |--------------------------------------------------------------------------
    | Image properties
    |--------------------------------------------------------------------------
    |
    | Here you can specify properties of generated image.
    |
    */

    'image' => [

        'width' => 240, // In pixels

        'height' => 100, // In pixels

        'transparent' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Prefered Image Library
    |--------------------------------------------------------------------------
    |
    | Here you can specify prefered image library for generating CAPTCHA image.
    | If none is specified, we'll try to detect available one.
    | Supported: imagick, gd
    |
    */

    'preferred_image_library' => 'gd',

    /*
    |--------------------------------------------------------------------------
    | Route
    |--------------------------------------------------------------------------
    |
    | Here you can specify route that will be used for fetching CAPTCHA image.
    |
    */

    'route' => 'captcha',

    /*
    |--------------------------------------------------------------------------
    | Refresh Query Param Name
    |--------------------------------------------------------------------------
    |
    | Here you can specify name of the query parameter
    | that will mark the request to refresh the code.
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
