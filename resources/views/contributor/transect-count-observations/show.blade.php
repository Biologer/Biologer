@extends('layouts.dashboard', ['title' => __('navigation.my_transect_count_observations')])

@section('content')
    <div class="box">
        <nz-transect-count-observation-sections-table
            list-route="api.transect-counts.transect-sections"
            view-route="contributor.transect-sections.show"
            edit-route="contributor.transect-sections.edit"
            delete-route="api.transect-sections.destroy"
            empty="{{ __('No data...') }}"
            show-status
            show-activity-log
            @role(['admin', 'curator'])
            show-observer
            @endrole
            :transect-count-observation-id="{{ $transectCountObservation->id }}"
        />
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
            <li class="is-active"><a>{{ __('navigation.transect_count_observations') }}</a></li>
        </ul>
    </div>
@endsection
