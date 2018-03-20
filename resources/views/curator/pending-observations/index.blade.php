@extends('layouts.dashboard', ['title' => __('navigation.pending_observations')])

@section('content')
    <div class="box">
        <nz-field-observations-table
            list-route="api.curator.pending-observations.index"
            edit-route="curator.pending-observations.edit"
            delete-route="api.field-observations.destroy"
            empty="{{ __('No data...') }}"
            @role(['admin', 'curator'])
            show-activity-log
            @endrole
            approvable
            approve-route="api.approved-field-observations-batch.store"
            markable-as-unidentifiable
            mark-as-unidentifiable-route="api.unidentifiable-field-observations-batch.store"
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

@section('createButton')
    <a href="{{ route('contributor.field-observations.create') }}" class="button is-secondary is-outlined">
        @include('components.icon', ['icon' => 'plus'])
        <span>{{ __('navigation.new_field_observation') }}</span>
    </a>
@endsection
