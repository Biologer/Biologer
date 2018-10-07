@extends('layouts.dashboard', ['title' => __('navigation.view_groups')])

@section('content')
    <div class="box">
        <nz-view-groups-table
            list-route="api.view-groups.index"
            edit-route="admin.view-groups.edit"
            delete-route="api.view-groups.destroy"
            empty="{{ __('No data...') }}"
        />
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
            <li class="is-active"><a>{{ __('navigation.view_groups') }}</a></li>
        </ul>
    </div>
@endsection

@section('createButton')
    <a href="{{ route('admin.view-groups.create') }}" class="button is-secondary is-outlined">
        @include('components.icon', ['icon' => 'plus'])
        <span>{{ __('navigation.add') }}</span>
    </a>
@endsection
