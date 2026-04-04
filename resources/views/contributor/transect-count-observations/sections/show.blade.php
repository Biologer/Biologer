@extends('layouts.dashboard', ['title' => __('navigation.my_transect_sections')])

@section('content')
    <div class="box">
        <nz-transect-section-transect-visits-table
            list-route="api.transect-sections.transect-visits"
            view-route="contributor.transect-visits.show"
            edit-route="contributor.transect-visits.edit"
            delete-route="api.transect-visits.destroy"
            empty="{{ __('No data...') }}"
            show-status
            show-activity-log
            @role(['admin', 'curator'])
            show-observer
            @endrole
            :transect-section-id="{{ $transectSection->id }}"
        />
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
            <li class="is-active"><a>{{ __('navigation.transect_sections') }}</a></li>
        </ul>
    </div>
@endsection
