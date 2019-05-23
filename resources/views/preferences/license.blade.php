@extends('layouts.dashboard', ['title' => __('navigation.preferences.license_preferences')])

@section('sidebar', Menu::preferencesSidebar())

@section('content')

@if(session('success'))
    <article class="message shadow is-success">
        <div class="message-body">
            {{ session('success') }}
        </div>
    </article>
@endif

<div class="box">
    <form action="{{ route('preferences.license') }}" method="POST">
        {{ csrf_field() }}
        {{ method_field('PATCH') }}

        @include('partials.licenses')

        <article class="message is-info">
            <div class="message-body">
                {!! __('<strong>Note:</strong> Changes to license preferences will act as defaults for newly added observations and photos, and will not affect old entries.') !!}
            </div>
        </article>

        <hr>

        <div class="field">
            <button type="submit" class="button is-primary">{{ __('buttons.save') }}</button>
        </div>
    </form>
</div>
@endsection

@section('breadcrumbs')
<div class="breadcrumb" aria-label="breadcrumbs">
    <ul>
        <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
        <li class="is-active"><a>{{ __('navigation.preferences.index') }}</a></li>
        <li class="is-active"><a>{{ __('navigation.preferences.license_preferences') }}</a></li>
    </ul>
</div>
@endsection
