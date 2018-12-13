@extends('layouts.dashboard', ['title' => __('navigation.announcements')])

@section('content')
    <div class="box">
        <nz-announcements-table
            list-route="api.announcements.index"
            edit-route="admin.announcements.edit"
            delete-route="api.announcements.destroy"
            empty="{{ __('No data...') }}"
        />
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
            <li class="is-active"><a>{{ __('navigation.announcements') }}</a></li>
        </ul>
    </div>
@endsection

@section('navigationActions')
    <a href="{{ route('admin.announcements.create') }}" class="button is-secondary is-outlined">
        @include('components.icon', ['icon' => 'plus'])
        <span>{{ __('navigation.new_announcement') }}</span>
    </a>
@endsection
