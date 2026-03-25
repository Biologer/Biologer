@extends('layouts.dashboard', ['title' => __('navigation.my_transect_sections')])

@section('content')
    <div class="box">
        <nz-transect-sections-table
            list-route="api.my.transect-sections.index"
            view-route="contributor.transect-sections.show"
            delete-route="api.transect-sections.destroy"
            empty="{{ __('No data...') }}"
            show-activity-log
            show-observer
        ></nz-transect-sections-table>
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
            <li class="is-active"><a>{{ __('navigation.transect-sections') }}</a></li>
        </ul>
    </div>
@endsection
