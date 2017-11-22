@extends('layouts.dashboard', ['title' => 'Pending Observations'])

@section('content')
    <div class="box">
        <nz-field-observations-table
            list-route="api.pending-observations.index"
            edit-route="admin.pending-observations.edit"
            delete-route="api.field-observations.destroy">
        </nz-field-observations-table>
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">Dashboard</a></li>
            <li class="is-active"><a>Pending Observations</a></li>
        </ul>
    </div>
@endsection

@section('createButton')
    <a href="{{ route('contributor.field-observations.create') }}" class="button is-secondary is-outlined">
        @include('components.icon', ['icon' => 'plus'])
        <span>New Field Observation</span>
    </a>
@endsection
