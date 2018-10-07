@extends('layouts.main', ['title' => $announcement->title])

@section('content')
    <div class="container py-8">
        <h1 class="is-size-4 mb-8">{{ $announcement->title }}</h1>

        <div class="content">
            {!! $announcement->message !!}
        </div>
    </div>
@endsection
