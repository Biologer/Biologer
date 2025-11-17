@extends('layouts.main', ['title' => __('about.citation')])

{{-- 1. CSRF token for post request --}}
@section('header')
@parent
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div class="box">
        <nz-citation>
            :community-name="{{ $communityName }}"
            :platform-year="{{ $platformYear }}"
            :platform-url="{{ $platformUrl }}"
        </nz-citation>
    </div>
@endsection
