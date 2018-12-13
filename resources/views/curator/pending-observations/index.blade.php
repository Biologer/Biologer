@extends('layouts.dashboard', ['title' => __('navigation.pending_observations')])

@section('content')
    <div class="box">
        <nz-field-observations-table
            list-route="api.curator.pending-observations.index"
            view-route="curator.pending-observations.show"
            edit-route="curator.pending-observations.edit"
            delete-route="api.field-observations.destroy"
            empty="{{ __('No data...') }}"
            @role(['admin', 'curator'])
            show-activity-log
            show-observer
            @endrole
            approvable
            approve-route="api.approved-field-observations-batch.store"
            markable-as-unidentifiable
            mark-as-unidentifiable-route="api.unidentifiable-field-observations-batch.store"
            export-url="{{ route('api.curator.pending-observation-exports.store') }}"
            :export-columns="{{ $exportColumns }}"
        />
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
            <li class="is-active"><a>{{ __('navigation.pending_observations') }}</a></li>
        </ul>
    </div>
@endsection

@section('navigationActions')
    <a href="{{ route('contributor.field-observations.create') }}" class="button is-secondary is-outlined">
        @include('components.icon', ['icon' => 'plus'])
        <span>{{ __('navigation.new_field_observation') }}</span>
    </a>
@endsection
