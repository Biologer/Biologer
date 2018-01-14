@extends('layouts.dashboard')

@section('content')
    <div class="box">
        <nz-user-form action="{{ route('api.users.update', $user) }}"
            method="put"
            redirect="{{ route('admin.users.index') }}"
            :user="{{ $user }}"
            :roles="{{ $roles }}">
        </nz-user-form>
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">Dashboard</a></li>
            <li><a href="{{ route('admin.users.index') }}">Users</a></li>
            <li class="is-active"><a>Edit</a></li>
        </ul>
    </div>
@endsection
