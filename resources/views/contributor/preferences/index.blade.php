@extends('layouts.dashboard')

@section('content')
<div class="box">
    <form action="{{ route('contributor.preferences.update') }}" method="POST">
        {{ csrf_field() }}
        {{ method_field('PATCH') }}

        @include('partials.licenses')

        <article class="message is-info">
            <div class="message-body">
                <strong>{{ __('Note') }}:</strong> {{ __('Changes to license preferences will act as defaults for newly added observations and photos, and will not affect old entries.') }}
            </div>
        </article>

        <hr>

        <div class="field">
            <button type="submit" class="button is-primary">{{ __('buttons.save') }}</button>
        </div>
    </form>
</div>
@endsection

@section('breadcrumbs')
<div class="breadcrumb" aria-label="breadcrumbs">
    <ul>
        <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
        <li class="is-active"><a>{{ __('navigation.preferences') }}</a></li>
    </ul>
</div>
@endsection
