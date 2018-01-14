@extends('layouts.dashboard')

@section('content')
    <nz-users-table list-route="api.users.index"
        edit-route="admin.users.edit"
        delete-route="api.users.destroy"
    ></nz-users-table>
@endsection
