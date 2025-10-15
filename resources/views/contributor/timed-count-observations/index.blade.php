@extends('layouts.dashboard', ['title' => __('navigation.my_timed_count_observations')])

@section('content')
    <div class="box">
        <nz-timed-count-observations-table
            list-route="api.my.timed-count-observations.index"
            view-route="contributor.timed-count-observations.show"
            delete-route="api.timed-count-observations.destroy"
            empty="{{ __('No data...') }}"
            show-activity-log
            show-observer
        ></nz-timed-count-observations-table>
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
            <li class="is-active"><a>{{ __('navigation.timed_count_observations') }}</a></li>
        </ul>
    </div>
@endsection

@section('navigationActions')
    <a href="{{ route('contributor.field-observations.create') }}" class="button is-secondary is-outlined">
        @include('components.icon', ['icon' => 'plus'])
        <span>{{ __('navigation.new_field_observation') }}</span>
    </a>
@endsection
