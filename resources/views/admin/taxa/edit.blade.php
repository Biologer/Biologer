@extends('layouts.dashboard', ['title' => 'Edit Taxon'])

@section('content')
    <div class="box">
        <nz-taxon-form
            action="{{ route('api.taxa.update', $taxon) }}"
            method="PUT"
            :ranks="{{ $ranks }}"
            :conventions="{{ $conventions }}"
            :red-lists="{{ $redLists }}"
            :red-list-categories="{{ $redListCategories }}"
            :stages="{{ $stages }}"
            :taxon="{{ $taxon }}"
        ></nz-taxon-form>
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">Dashboard</a></li>
            <li><a href="{{ route('admin.taxa.index') }}">Taxa</a></li>
            <li class="is-active"><a>Edit</a></li>
        </ul>
    </div>
@endsection
