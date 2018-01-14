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
