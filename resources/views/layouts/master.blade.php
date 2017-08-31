<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name') }}</title>

        @stack('styles')
        <link rel="stylesheet" href="/css/app.css">
    </head>
    <body>
        <div id="app">
            @yield('content')
        </div>

        @stack('beforeScripts')
        <script src="{{ mix('js/app.js') }}"></script>
        @stack('afterScripts')
    </body>
</html>
