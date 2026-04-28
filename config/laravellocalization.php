<?php

use Illuminate\Support\Arr;

// Active locales that will be available to the user in application.
$activeLocales = array_map('trim', explode(',', env('ACTIVE_LOCALES', 'en')));

// Uncomment the languages that your site supports - or add new ones.
// These are sorted by the native name, which is the order you might show them in a language selector.
// Regional languages are sorted by their base language, so "British English" sorts as "English, British"
$supportedLocales = [
    'bs' => ['name' => 'Bosnian', 'script' => 'Latn', 'native' => 'Bosanski', 'regional' => 'bs_BA'],
    'en' => ['name' => 'English', 'script' => 'Latn', 'native' => 'English', 'regional' => 'en_GB'],
    'hr' => ['name' => 'Croatian', 'script' => 'Latn', 'native' => 'Hrvatski', 'regional' => 'hr_HR'],
    'me' => ['name' => 'Montenegrin', 'script' => 'Latn', 'native' => 'Crnogorski', 'regional' => 'sr_ME'],
    'sr' => ['name' => 'Serbian (Cyrillic)', 'script' => 'Cyrl', 'native' => 'Српски', 'regional' => 'sr_RS'],
    'sr-Latn' => ['name' => 'Serbian (Latin)', 'script' => 'Latn', 'native' => 'Srpski', 'regional' => 'sr_RS'],
    //'hu'          => ['name' => 'Hungarian',              'script' => 'Latn', 'native' => 'magyar', 'regional' => 'hu_HU'],
    //'ro'          => ['name' => 'Romanian',               'script' => 'Latn', 'native' => 'română', 'regional' => 'ro_RO'],
    //'sq'          => ['name' => 'Albanian',               'script' => 'Latn', 'native' => 'shqip', 'regional' => 'sq_AL'],
    //'sk'          => ['name' => 'Slovak',                 'script' => 'Latn', 'native' => 'slovenčina', 'regional' => 'sk_SK'],
    //'sl'          => ['name' => 'Slovene',                'script' => 'Latn', 'native' => 'slovenščina', 'regional' => 'sl_SI'],
    //'sh'          => ['name' => 'Serbo-Croatian',         'script' => 'Latn', 'native' => 'srpskohrvatski', 'regional' => ''],
    //'el'          => ['name' => 'Greek',                  'script' => 'Grek', 'native' => 'Ελληνικά', 'regional' => 'el_GR'],
    //'bg'          => ['name' => 'Bulgarian',              'script' => 'Cyrl', 'native' => 'български', 'regional' => 'bg_BG'],
    //'mk'          => ['name' => 'Macedonian',             'script' => 'Cyrl', 'native' => 'македонски', 'regional' => 'mk_MK'],
];

return [

    'supportedLocales' => Arr::only($supportedLocales, $activeLocales),

    // Negotiate for the user locale using the Accept-Language header if it's not defined in the URL?
    // If false, system will take app.php locale attribute
    'useAcceptLanguageHeader' => true,

    // If LaravelLocalizationRedirectFilter is active and hideDefaultLocaleInURL
    // is true, the url would not have the default application language
    //
    // IMPORTANT - When hideDefaultLocaleInURL is set to true, the unlocalized root is treated as the applications default locale "app.locale".
    // Because of this language negotiation using the Accept-Language header will NEVER occur when hideDefaultLocaleInURL is true.
    //
    'hideDefaultLocaleInURL' => true,

    // If you want to display the locales in particular order in the language selector you should write the order here.
    // CAUTION: Please consider using the appropriate locale code otherwise it will not work
    // Example: 'localesOrder' => ['es','en'],
    'localesOrder' => [],

    // If you want to use custom lang url segments like 'at' instead of 'de-AT', you can use the mapping to allow the LanguageNegotiator to assign the descired locales based on HTTP Accept Language Header. For example you want ot use 'at', so map HTTP Accept Language Header 'de-AT' to 'at' (['de-AT' => 'at']).
    'localesMapping' => [],

    // Locale suffix for LC_TIME and LC_MONETARY
    // Defaults to most common ".UTF-8". Set to blank on Windows systems, change to ".utf8" on CentOS and similar.
    'utf8suffix' => env('LARAVELLOCALIZATION_UTF8SUFFIX', '.UTF-8'),

    // URLs which should not be processed, e.g. '/nova', '/nova/*', '/nova-api/*' or specific application URLs
    // Defaults to []
    'urlsIgnored' => [],
];
