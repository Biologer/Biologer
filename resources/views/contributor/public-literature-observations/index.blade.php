@extends('layouts.dashboard', ['title' => __('navigation.literature_observations')])

@section('content')
    <div class="box">
        <nz-literature-observations-table
            list-route="api.literature-observations.index"
            view-route="contributor.public-literature-observations.show"
            empty="{{ __('No data...') }}"
            export-url="{{ route('api.literature-observation-exports.store') }}"
            :export-columns="{{ $exportColumns }}"
        />
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
            <li class="is-active"><a>{{ __('navigation.literature_observations') }}</a></li>
        </ul>
    </div>
@endsection
