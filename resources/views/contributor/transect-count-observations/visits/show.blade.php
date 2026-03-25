@extends('layouts.dashboard', ['title' => __('navigation.my_transect_visits')])

@section('content')
    <div class="box">
        <nz-transect-visit-field-observations-table
            list-route="api.transect-visits.field-observations"
            view-route="contributor.field-observations.show"
            edit-route="contributor.field-observations.edit"
            delete-route="api.field-observations.destroy"
            empty="{{ __('No data...') }}"
            show-status
            show-activity-log
            @role(['admin', 'curator'])
            show-observer
            @endrole
            export-url="{{ route('api.my.field-observation-exports.store') }}"
            :transect-visit-id="{{ $transectVisit->id }}"
        />
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
            <li class="is-active"><a>{{ __('navigation.transect_visits') }}</a></li>
        </ul>
    </div>
@endsection
