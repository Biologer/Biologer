@extends('layouts.dashboard', ['title' => __('navigation.literature_observations')])

@section('content')
    <div class="box">
        <nz-literature-observations-table
            list-route="api.literature-observations.index"
            view-route="admin.literature-observations.show"
            edit-route="admin.literature-observations.edit"
            delete-route="api.literature-observations.destroy"
            empty="{{ __('No data...') }}"
            show-activity-log
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

@section('navigationActions')
    <a href="{{ route('admin.literature-observations.create') }}" class="button is-secondary is-outlined">
        @include('components.icon', ['icon' => 'plus'])
        <span>{{ __('navigation.new_literature_observation') }}</span>
    </a>

    <a href="{{ route('admin.literature-observations-import.index') }}" class="button is-secondary is-outlined ml-2">
        @include('components.icon', ['icon' => 'upload'])
        <span>{{ __('navigation.import') }}</span>
    </a>
@endsection
