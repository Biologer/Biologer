@extends('layouts.dashboard', ['title' => __('navigation.publications')])

@section('content')
    <div class="box">
        <nz-announcements-table
            list-route="api.publications.index"
            edit-route="admin.publications.edit"
            delete-route="api.publications.destroy"
            empty="{{ __('No data...') }}"
        />
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
            <li class="is-active"><a>{{ __('navigation.publications') }}</a></li>
        </ul>
    </div>
@endsection

@section('navigationActions')
    <a href="{{ route('admin.publications.create') }}" class="button is-secondary is-outlined">
        @include('components.icon', ['icon' => 'plus'])
        <span>{{ __('navigation.new_publication') }}</span>
    </a>
@endsection
