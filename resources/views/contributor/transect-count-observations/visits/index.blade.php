@extends('layouts.dashboard', ['title' => __('navigation.my_transect_visits')])

@section('content')
    <div class="box">
        <nz-transect-visits-table
            list-route="api.my.transect-visits.index"
            view-route="contributor.transect-visits.show"
            delete-route="api.transect-visits.destroy"
            empty="{{ __('No data...') }}"
            show-activity-log
            show-observer
        ></nz-transect-visits-table>
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
