<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class Localization
{
    public static function strings()
    {
        $locale = app()->getLocale();

        return Cache::rememberForever("localizationStrings.{$locale}", function () use ($locale) {
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

    public static function getAllTranslations($key)
    {
        $translations = [];
        $locales = LaravelLocalization::getSupportedLanguagesKeys();

        foreach ($locales as $locale) {
            $translations[$locale] = __($key, [], $locale);
        }

        return $translations;
    }

    /**
     * Transform translations from request format to format for storing.
     *
     * @param  array  $data
     * @return array
     */
    public static function transformTranslations($data)
    {
        $newData = [];
        $locales = LaravelLocalization::getSupportedLanguagesKeys();

        foreach ($locales as $locale) {
            foreach ($data as $attribute => $values) {
                $newData[$locale][$attribute] = array_get($values, $locale);
            }
        }

        return $newData;
    }
}
