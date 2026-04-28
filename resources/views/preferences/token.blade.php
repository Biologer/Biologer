@extends('layouts.dashboard', ['title' => __('navigation.preferences.token')])

@section('sidebar', Menu::preferencesSidebar())

@section('content')
    <div class="box">
        <nz-token-preference
            generate-route="preferences.token.generate"
            revoke-route="preferences.token.revoke"
            :tokens=" {{ $tokens }} "
        >
        </nz-token-preference>
    </div>
@endsection

@section('breadcrumbs')
<div class="breadcrumb" aria-label="breadcrumbs">
    <ul>
        <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
        <li class="is-active"><a>{{ __('navigation.preferences.index') }}</a></li>
        <li class="is-active"><a>{{ __('navigation.preferences.token') }}</a></li>
    </ul>
</div>
@endsection
