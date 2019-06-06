@extends('layouts.dashboard', ['title' => __('navigation.public_field_observations')])

@section('content')
    <div class="box">
        <nz-field-observations-table
            list-route="api.public-field-observations.index"
            view-route="contributor.public-field-observations.show"
            empty="{{ __('No data...') }}"
            show-observer
            show-status
            export-url="{{ route('api.public-field-observation-exports.store') }}"
            :export-columns="{{ $exportColumns }}"
        />
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
            <li class="is-active"><a>{{ __('navigation.public_field_observations') }}</a></li>
        </ul>
    </div>
@endsection
