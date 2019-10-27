@extends('layouts.dashboard', ['title' => __('navigation.literature_observations_import')])

@section('content')
    <div class="box">
        <div class="message">
            {{-- <div class="message-body">{{ __('pages.field_observations_import.short_info') }}</div> --}}
        </div>

        <nz-literature-observations-import
            :columns="{{ $columns }}"
            :running-import="{{ $import ?? 'null' }}"
            :cancellable-statuses="{{ $cancellableStatuses }}"
        />
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
            <li><a href="{{ route('admin.literature-observations.index') }}">{{ __('navigation.literature_observations') }}</a></li>
            <li class="is-active"><a>{{ __('navigation.import') }}</a></li>
        </ul>
    </div>
@endsection

@section('navigationActions')
    {{-- <a href="{{ route('contributor.literature-observations-import.guide') }}"><i class="fa fa-question"></i> {{ __('Help') }}</a> --}}
@endsection
