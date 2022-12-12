@extends('layouts.dashboard', ['title' => __('navigation.observation_details')])

@section('content')
    <div class="box">
        @include('partials.field-observation-details', compact('fieldObservation'))

        @if ($fieldObservation->isPending())
            <div class="level">
                <div class="level-left">
                    @if(auth()->user()->can('update', $fieldObservation))
                        <div class="level-item">
                            <a
                                href="{{ route('curator.pending-observations.edit', $fieldObservation) }}"
                                class="button is-primary is-outlined"
                            >{{ __('buttons.edit') }}</a>
                        </div>
                    @endif
                </div>

                <nz-field-observation-approval
                    :field-observation="{{ $fieldObservation }}"

                    @if(auth()->user()->can('approve', $fieldObservation))
                    approve-url="{{ route('api.approved-field-observations-batch.store') }}"
                    @endif

                    @if(auth()->user()->can('markAsUnidentifiable', $fieldObservation))
                    mark-as-unidentifiable-url="{{ route('api.unidentifiable-field-observations-batch.store') }}"
                    @endif

                    redirect-url="{{ route('curator.pending-observations.index') }}"
                ></nz-field-observation-approval>
            </div>
        @endif

        <hr>

        <h2 class="is-size-4">{{ __('Activity Log') }}</h2>

        <nz-field-observation-activity-log :activities="{{ $fieldObservation->activity }}"/>
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
            <li><a href="{{ route('curator.pending-observations.index') }}">{{ __('navigation.pending_observations') }}</a></li>
            <li class="is-active"><a>{{ $fieldObservation->id }}</a></li>
        </ul>
    </div>
@endsection
