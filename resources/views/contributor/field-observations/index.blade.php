@extends('layouts.dashboard', ['title' => __('navigation.my_field_observations')])

@section('content')
    <div class="box">
        <nz-field-observations-table
            list-route="api.my.field-observations.index"
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

@section('createButton')
    <a href="{{ route('contributor.field-observations.create') }}" class="button is-secondary is-outlined">
        @include('components.icon', ['icon' => 'plus'])
        <span>{{ __('navigation.new_observation') }}</span>
    </a>
@endsection
