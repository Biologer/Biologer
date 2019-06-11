@extends('layouts.dashboard', ['title' => __('navigation.my_field_observations')])

@section('content')
    <div class="box">
        <nz-field-observations-table
            list-route="api.my.field-observations.index"
            view-route="contributor.field-observations.show"
            edit-route="contributor.field-observations.edit"
            delete-route="api.field-observations.destroy"
            empty="{{ __('No data...') }}"
            show-status
            @role(['admin', 'curator'])
            show-activity-log
            show-observer
            @endrole
            export-url="{{ route('api.my.field-observation-exports.store') }}"
            :export-columns="{{ $exportColumns }}"
        />
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
            <li class="is-active"><a>{{ __('navigation.my_field_observations') }}</a></li>
        </ul>
    </div>
@endsection

@section('navigationActions')
    <a href="{{ route('contributor.field-observations.create') }}" class="button is-secondary is-outlined">
        @include('components.icon', ['icon' => 'plus'])
        <span>{{ __('navigation.new_observation') }}</span>
    </a>

    <a href="{{ route('contributor.field-observations-import.index') }}" class="button is-secondary is-outlined ml-2">
        @include('components.icon', ['icon' => 'upload'])
        <span>{{ __('navigation.import') }}</span>
    </a>
@endsection
