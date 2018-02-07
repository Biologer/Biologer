<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

class Localization
{
    public static function strings()
    {
        $locale = app()->getLocale();

        return Cache::rememberForever("localizationString.{$locale}", function () use ($locale) {
            return array_merge(
                static::loadJson($locale),
                static::loadTranslations($locale)
            );
        });
    }

    protected static function loadJson($locale)
    {
        $json = resource_path("lang/{$locale}.json");

        if (! file_exists($json)) {
            return [];
        }

        return json_decode(file_get_contents($json), true);
    }

    protected static function loadTranslations($locale)
    {
        $strings = [];
        $files = glob(resource_path("lang/{$locale}/*.php"));

        foreach ($files as $file) {
            $name = basename($file, '.php');
            $strings[$name] = require $file;
        }

        return $strings;
    }
}
