@extends('layouts.dashboard', ['title' => __('navigation.taxonomy')])

@section('content')
    <div class="box">
        <nz-taxonomy-table
            list-route="api.taxa.index"
            sync-route="admin.taxonomy.sync"
            check-route="admin.taxonomy.check"
            connect-route="admin.taxonomy.connect"
            disconnect-route="admin.taxonomy.disconnect"
            empty="{{ __('No data...') }}">
        </nz-taxonomy-table>
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
            <li class="is-active"><a>{{ __('navigation.taxonomy') }}</a></li>
        </ul>
    </div>
@endsection

@section('navigationActions')

@endsection
