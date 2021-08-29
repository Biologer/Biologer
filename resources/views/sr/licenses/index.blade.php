@extends('layouts.main', ['title' => __('navigation.licenses')])

@section('content')
    <div class="container">
        <h1 class="title is-size-1 has-text-centered mt-4">Доступне лиценце</h1>

        <div class="columns">
            <div class="column">
                <h2 class="is-size-3 mb-2">Лиценце података</h2>

                @foreach (\App\License::all() as $license)
                    <p>
                        <a href="{{ $license->link }}">{{ $license->name() }}</a>
                    </p>
                @endforeach
            </div>

            <div class="column">
                <h2 class="is-size-3 mb-2">Лиценце фотографија</h2>

                @foreach (\App\ImageLicense::all() as $license)
                    <p>
                        <a href="{{ $license->link }}">{{ $license->name() }}</a>
                    </p>
                @endforeach
            </div>
        </div>
    </div>
@endsection
