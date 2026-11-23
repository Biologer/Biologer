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
            markable-as-unidentifiable
            mark-as-unidentifiable-route="api.unidentifiable-field-observations-batch.store"
            movable-to-pending
            move-to-pending-route="api.pending-field-observations-batch.store"
            approvable
            approve-route="api.approved-field-observations-batch.store"
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
