@extends('layouts.dashboard')

@section('content')
    <div class="level box">
        <div class="level-item has-text-centered">
            <div>
                <p class="heading">My Field Observations</p>
                <p class="title">{{ $observationCount }}</p>
            </div>
        </div>
        <div class="level-item has-text-centered">
            <div>
                <p class="heading">Approved</p>
                <p class="title">0</p>
            </div>
        </div>
        <div class="level-item has-text-centered">
            <div>
                <p class="heading">Unsolved</p>
                <p class="title">0</p>
            </div>
        </div>
        <div class="level-item has-text-centered">
            <div>
                <p class="heading">Pending</p>
                <p class="title">0</p>
            </div>
        </div>
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li class="is-active"><a>Dashboard</a></li>
        </ul>
    </div>
@endsection

@section('createButton')
    <a class="button is-secondary is-outlined" href="{{ route('contributor.field-observations.create') }}">
        @include('components.icon', ['icon' => 'plus'])
        <span>New Observation<span>
    </a>
@endsection
