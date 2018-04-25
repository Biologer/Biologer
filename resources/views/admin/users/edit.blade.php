@extends('layouts.dashboard', ['title' => __('navigation.edit_user')])

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
            <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
            <li><a href="{{ route('admin.users.index') }}">{{ __('navigation.users') }}</a></li>
            <li class="is-active"><a>{{ __('navigation.edit') }}</a></li>
        </ul>
    </div>
@endsection
