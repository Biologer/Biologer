@extends('layouts.contributor')

@section('main')
    <div class="container">
        <section class="section">
            <div class="columns">
                <div class="column is-one-third">
                    <div class="box has-text-centered">
                        <h3 class="is-uppercase is-size-6">Observations</h3>
                        <div class="is-size-1">
                            {{ $observationCount }}
                        </div>
                        <a href="{{ route('contributor.field-observations.index') }}">See all</a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
