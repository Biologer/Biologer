@extends('layouts.dashboard', ['title' => __('navigation.my_transect_count_observations')])

@section('content')
    <div class="box">
        <nz-transect-count-observations-table
            list-route="api.my.transect-count-observations.index"
            view-route="contributor.transect-count-observations.show"
            delete-route="api.transect-count-observations.destroy"
            empty="{{ __('No data...') }}"
            show-activity-log
            show-observer
        ></nz-transect-count-observations-table>
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
