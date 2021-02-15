@extends('layouts.dashboard')

@section('content')

<div class="level box">
    <div class="level-item has-text-centered">
        <div>
            <p class="heading">{{ __('dashboard.my_field_observations') }}</p>
            <p class="title">{{ $observationCount }}</p>
        </div>
    </div>
    <div class="level-item has-text-centered">
        <div>
            <p class="heading">{{ __('dashboard.approved') }}</p>
            <p class="title">{{ $approvedObservationCount }}</p>
        </div>
    </div>
    <div class="level-item has-text-centered">
        <div>
            <p class="heading">{{ __('dashboard.unidentifiable') }}</p>
            <p class="title">{{ $unidentifiableObservationCount }}</p>
        </div>
    </div>
    <div class="level-item has-text-centered">
        <div>
            <p class="heading">{{ __('dashboard.pending') }}</p>
            <p class="title">{{ $pendingObservationCount }}</p>
        </div>
    </div>
</div>
@endsection

@section('breadcrumbs')
<div class="breadcrumb" aria-label="breadcrumbs">
    <ul>
        <li class="is-active"><a>{{ __('navigation.dashboard') }}</a></li>
    </ul>
</div>
@endsection

@section('navigationActions')
<a class="button is-secondary is-outlined" href="{{ route('contributor.field-observations.create') }}">
    <span class="icon">
        <svg class="w-6 h-6" version="1.1" viewBox="0 0 15 25" xmlns="http://www.w3.org/2000/svg">
            <g transform="translate(0,-272)">
                <path d="m0.80813 276.75c-2.9012 8.0576 2.629 15.434 6.7824 20.253 5.4264-5.9242 9.0259-14.118 6.6797-20.253-1.4622-3.7381-4.0744-4.7423-6.675-4.7469-2.6006-5e-3 -5.1671 1.0368-6.7871 4.7469zm5.7842 0c-0.5104-0.57191-0.66856-0.74364-0.52363-1.4103 0.06698-0.30813 0.34809-1.502 1.5268-1.5106 1.1211-8e-3 1.3843 1.1216 1.4707 1.5038 0.1482 0.65541-0.02456 1.0198-0.47234 1.417 0.44775 0.12094 0.84301 0.3557 1.5845 0.95081 0.12348 0.16669 1.4288-1.5418 2.0801-0.15319 0.92142 1.9645-1.0963 1.9493-0.99245 2.2227 0.56013 0.85613 0.85203 2.3476 0.02958 4.2464-0.21791 0.18106 2.9745 0 0.45294 2.4486-0.82116 0.79739-1.9392-0.3822-1.9392-0.3822-0.14398 0.0643-1.1479 0.60027-1.3168 0.62454-0.73147 0.10511-0.75542 1.4936-0.88004 2.0988-0.17144-0.55681-0.14611-1.969-0.92025-2.1441-0.23702-0.0536-1.1516-0.4234-1.376-0.54245-0.22286-0.11823-1.0085 1.2909-2.0539 0.27715-2.4544-2.3803 0.94266-2.1638 0.83914-2.4114-0.64245-1.5363-1.2139-1.8637-0.47427-4.1747 0.09352-0.29222-1.8675-0.62233-0.93104-2.2836 0.85192-1.5113 1.8753 0.3771 2.0856 0.16335 0.5423-0.54786 1.285-0.76187 1.8104-0.94073z"/>
            </g>
        </svg>
    </span>

    <span>{{ __('navigation.new_observation') }}<span>
</a>
@endsection
