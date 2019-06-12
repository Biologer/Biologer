@extends('layouts.main')

@section('content')
    <section class="section is-hidden-touch mb-8">
        <div class="container has-text-centered">
            <h1><img src="{{ asset('img/logo.svg') }}" class="image banner-image mx-auto" alt="{{ config('app.name') }}"></h1>
        </div>
    </section>

    <div class="container py-8 px-4 desktop:px-16">
        <p class="is-size-4 mb-8">
            {{ __('pages.home.welcome') }}
            {{ __('pages.home.stats', compact('community', 'userCount', 'observationCount')) }}
        </p>

        <div class="columns">
            <div class="column is-full-mobile is-one-third is-offset-2">
                <a href="{{ route('groups.index') }}" class="button is-primary is-outlined is-fullwidth"><b>{{ __('pages.home.browse') }}</b></a>
            </div>

            <div class="column is-full-mobile is-one-third">
                <a href="{{ config('biologer.android_app_url') }}" class="button is-outlined is-fullwidth" target="_blank" title="{{ __('pages.home.android_title') }}">
                    <i class="fa fa-android mr-2"></i> {{ __('pages.home.android_link') }}
                </a>
            </div>
        </div>

        <section class="mt-8">
            @if ($announcements->isNotEmpty())
                <h2 class="is-size-2 has-text-centered mb-8">{{ __('pages.home.announcements.title') }}</h2>

                @each('partials.announcement', $announcements, 'announcement')

                <a href="{{ route('announcements.index') }}">{{ __('pages.home.announcements.see_all') }}</a>
            @endif
        </section>
    </div>
@endsection
