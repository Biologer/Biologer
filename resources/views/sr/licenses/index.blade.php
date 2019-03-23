@extends('layouts.main', ['title' => __('navigation.licenses')])

@section('content')
    <div class="container">
        <h1 class="title is-1 has-text-centered mt-4">Dostupne licence</h1>

        @foreach (\App\License::allActive() as $license)
            <div>
                <a href="{{ $license->link }}">{{ $license->name() }}</a>
            </div>
        @endforeach
    </div>
@endsection
