<?php

namespace App\Support;

class Dataset
{
    public static function default()
    {
        return parse_url(config('app.url'), PHP_URL_HOST);
    }
}
