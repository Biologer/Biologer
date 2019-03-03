<?php

namespace App\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class Localization
{
    /**
     * Get all translation strings for current locale keyed by dotted string.
     *
     * @param  string|null  $locale
     * @return array
     */
    public static function strings($locale = null)
    {
        $locale = $locale ?? app()->getLocale();

        return Cache::rememberForever("localizationStrings.{$locale}", function () use ($locale) {
            return Arr::dot(array_merge(
                static::loadJson($locale),
                static::loadTranslations($locale)
            ));
        });
    }

    /**
     * Load translations from JSON files for locale.
     *
     * @param  string  $locale
     * @return array
     */
    protected static function loadJson($locale)
    {
        $json = resource_path("lang/{$locale}.json");

        if (! file_exists($json)) {
            return [];
        }

        return json_decode(file_get_contents($json), true);
    }

    /**
     * Load all translations for locale.
     *
     * @param  string  $locale
     * @return array
     */
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

    /**
     * Get all translations.
     *
     * @param  string  $key
     * @return array
     */
    public static function getAllTranslations($key)
    {
        $translations = [];

        foreach (LaravelLocalization::getSupportedLanguagesKeys() as $locale) {
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

        foreach (LaravelLocalization::getSupportedLanguagesKeys() as $locale) {
            foreach ($data as $attribute => $values) {
                $newData[$locale][$attribute] = Arr::get($values, $locale);
            }
        }

        return $newData;
    }

    /**
     * Clear localization strings cache.
     *
     * @return void
     */
    public static function clearCache()
    {
        foreach (LaravelLocalization::getSupportedLanguagesKeys() as $locale) {
            Cache::forget("localizationStrings.{$locale}");
        }
    }

    /**
     * Cache localization strings.
     *
     * @return void
     */
    public static function cache()
    {
        foreach (LaravelLocalization::getSupportedLanguagesKeys() as $locale) {
            static::strings($locale);
        }
    }
}
