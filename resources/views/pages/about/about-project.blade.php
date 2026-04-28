@extends('layouts.main', ['title' => __('about.project_title')])

@section('content')
<section class="section content">
    <div class="container">
        <h1>{{ __('about.project_title') }}</h1>

        @php
        $currentLocale = app()->getLocale();

        $pathPrefixMap = [
        'en' => '',
        'sr' => 'sr/',
        'sr-Latn' => 'sr_latin/',
        'hr' => 'hr/',
        'bs' => 'bs/',
        // Mapping Montenegrin to Serbian Latin since 'me/' isn't available yet
        'me' => 'sr_latin/',
        ];

        $prefix = $pathPrefixMap[$currentLocale] ?? '';

        $pageSlug = 'pages/about/';
        if ($currentLocale === 'en') {
        $pageSlug .= 'index.html';
        }

        $biologerOrgUrl = 'https://biologer.org/' . $prefix . $pageSlug;
        @endphp

        <div class="columns is-vcentered">

            {{-- COLUMN 1: Project Description --}}
            <div class="column is-two-thirds">
                <div class="box has-background-light" style="height: 100%; display: flex; align-items: center;">
                    <img src="{{ asset('img/ic_map.png') }}" alt="Biologer Map Icon" style="margin-right: 25px; max-height: 105px;">
                    <p class="has-text-justified" style="margin-right: 15px;">
                        {{ __('about.project_description') }}
                    </p>
                </div>
            </div>

            {{-- COLUMN 2: Link to Biologer.org --}}
            <div class="column is-one-third">
                <div class="box has-background-info-light has-text-centered" style="height: 100%; padding: 2rem;">
                    <p class="is-size-6 mb-4">
                        {{ __('about.box_description') }}
                    </p>
                    <a
                        href="{{ $biologerOrgUrl }}"
                        class="button is-info is-responsive is-fullwidth"
                        target="_blank"
                        rel="noopener noreferrer">
                        <span class="icon">
                            <i class="fa fa-globe"></i>
                        </span>
                        <span>{{ __('about.button_text') }}</span>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection