<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <meta name="description" content="{{ __('pages.home.welcome') }}">

        <link rel="canonical" href="{{ LaravelLocalization::getNonLocalizedURL() }}" />

        <link rel="alternate" hreflang="x-default" href="{{ LaravelLocalization::getNonLocalizedURL() }}" />

        @foreach(LaravelLocalization::getSupportedLanguagesKeys() as $localeCode)
            <link rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}" />
        @endforeach

        @if(empty($title))
            <title>{{ config('app.name') }}</title>
        @else
            <title>{{ $title.' | '.config('app.name') }}</title>
        @endif

        <link rel="shortcut icon" sizes="16x16" href="/favicon-16x16.png">
        <link rel="shortcut icon" sizes="32x32" href="/favicon-32x32.png">
        <link rel="shortcut icon" sizes="64x64" href="/favicon-64x64.png">
        <link rel="shortcut icon" sizes="96x96" href="/favicon-96x96.png">

        @stack('styles')
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        @include('script.app')

        @routes()

        @stack('headerScripts')
    </head>
    <body>
        <div id="app" class="is-flex flex-col min-h-screen">
            @yield('body')
        </div>

        @include('cookieConsent::index')

        @stack('beforeScripts')
        <script src="{{ mix('js/app.js') }}"></script>
        @stack('afterScripts')
    </body>
</html>
