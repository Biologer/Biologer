@extends('layouts.dashboard', ['title' => __('navigation.my_timed_count_observations')])

@section('content')
    <div class="box">
        <nz-timed-count-field-observations-table
            list-route="api.timed-counts.field-observations"
            view-route="admin.field-observations.show"
            edit-route="admin.field-observations.edit"
            delete-route="api.field-observations.destroy"
            empty="{{ __('No data...') }}"
            show-status
            show-activity-log
            @role(['admin', 'curator'])
            show-observer
            markable-as-unidentifiable
            mark-as-unidentifiable-route="api.unidentifiable-field-observations-batch.store"
            movable-to-pending
            move-to-pending-route="api.pending-field-observations-batch.store"
            approvable
            approve-route="api.approved-field-observations-batch.store"
            @endrole
            export-url="{{ route('api.my.field-observation-exports.store') }}"
            :timed-count-observation-id="{{ $timedCountObservation->id }}"
        />
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
