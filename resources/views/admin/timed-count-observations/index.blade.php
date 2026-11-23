@extends('layouts.dashboard', ['title' => __('navigation.all_timed_count_observations')])

@section('content')
    <div class="box">
        <nz-timed-count-observations-table
            list-route="api.timed-count-observations.index"
            view-route="admin.timed-count-observations.show"
            delete-route="api.timed-count-observations.destroy"
            empty="{{ __('No data...') }}"
            show-activity-log
            show-observer
        />
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
            <li class="is-active"><a>{{ __('navigation.all_timed_count_observations') }}</a></li>
        </ul>
    </div>
@endsection
