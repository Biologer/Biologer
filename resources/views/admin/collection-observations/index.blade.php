@extends('layouts.dashboard', ['title' => __('navigation.collection_observations')])

@section('content')
    <div class="box">
        <nz-collection-observations-table
            list-route="api.collection-observations.index"
            view-route="admin.collection-observations.show"
            edit-route="admin.collection-observations.edit"
            delete-route="api.collection-observations.destroy"
            empty="{{ __('No data...') }}"
            show-activity-log
            {{-- export-url="{{ route('api.collection-observation-exports.store') }}" --}}
            {{-- :export-columns="{{ $exportColumns }}" --}}
        />
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
            <li class="is-active"><a>{{ __('navigation.collection_observations') }}</a></li>
        </ul>
    </div>
@endsection

@section('navigationActions')
    <a href="{{ route('admin.collection-observations.create') }}" class="button is-secondary is-outlined">
        @include('components.icon', ['icon' => 'plus'])
        <span>{{ __('navigation.new_collection_observation') }}</span>
    </a>

    {{-- <a href="{{ route('admin.collection-observations-import.index') }}" class="button is-secondary is-outlined ml-2">
        @include('components.icon', ['icon' => 'upload'])
        <span>{{ __('navigation.import') }}</span>
    </a> --}}
@endsection
