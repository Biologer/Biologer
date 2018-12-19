@extends('layouts.main', ['title' => $announcement->title])

@section('content')
    <section class="section content">
        <div class="container">
            <h1 class="is-size-4 mb-8">{{ $announcement->title }}</h1>

            {!! $announcement->message !!}
        </div>
    <section>
@endsection
