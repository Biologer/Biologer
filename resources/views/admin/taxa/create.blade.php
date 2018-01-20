@extends('layouts.dashboard', ['title' => 'New Taxon'])

@section('content')
    <div class="box">
        <nz-taxon-form
            action="{{ route('api.taxa.store') }}"
            method="POST"
            :ranks="{{ $ranks }}"
            :conservation-lists="{{ $conservationLists }}"
            :red-lists="{{ $redLists }}"
            :red-list-categories="{{ $redListCategories }}"
            :stages="{{ $stages }}"
        ></nz-taxon-form>
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">Dashboard</a></li>
            <li><a href="{{ route('admin.taxa.index') }}">Taxa</a></li>
            <li class="is-active"><a>New</a></li>
        </ul>
    </div>
@endsection
