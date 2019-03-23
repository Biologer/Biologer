@extends('layouts.main', ['title' => __('navigation.licenses')])

@section('content')
    <div class="container">
        <h1 class="title is-1 has-text-centered mt-4">Available Licenses</h1>

        @foreach (\App\License::allActive() as $license)
            <p>
                <a href="{{ $license->link }}">{{ $license->name() }}</a>
            </p>
        @endforeach
    </div>
@endsection
