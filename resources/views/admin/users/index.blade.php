@extends('layouts.dashboard')

@section('content')
    <div class="box">
        <nz-users-table list-route="api.users.index"
        edit-route="admin.users.edit"
        delete-route="api.users.destroy"
        ></nz-users-table>
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
            <li class="is-active"><a>{{ __('navigation.users') }}</a></li>
        </ul>
    </div>
@endsection
