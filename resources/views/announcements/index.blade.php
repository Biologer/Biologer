@extends('layouts.main', ['title' => __('pages.announcements.title')])

@section('content')
    <div class="container pb-8 pt-4">
        <h2 class="is-size-2 has-text-centered mb-8">{{ __('pages.announcements.title') }}</h2>

        @if ($announcements->isNotEmpty())
            @each('partials.announcement', $announcements, 'announcement')
        @else
            {{ __('pages.announcements.no_announcements') }}
        @endif

        {{ $announcements->links() }}
    </div>
@endsection
