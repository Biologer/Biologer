@extends('layouts.dashboard', ['title' => __('navigation.preferences.general_preferences')])

@section('sidebar', Menu::preferencesSidebar())

@section('content')

@if(session('success'))
    <article class="message shadow is-success">
        <div class="message-body">
            {{ session('success') }}
        </div>
    </article>
@endif

<div class="box">
    <h2 class="is-size-4">{{ __('navigation.preferences.general_preferences') }}</h2>

    <hr>

    <form action="{{ route('preferences.general') }}" method="POST">
        {{ csrf_field() }}
        {{ method_field('PATCH') }}

        <div class="columns">
            <div class="column">
                <div class="field is-required">
                    <label class="label" for="first_name">{{ __('labels.register.first_name') }}</label>

                    <div class="control">
                        <input
                            id="first_name"
                            type="text"
                            name="first_name"
                            class="input{{ $errors->has('first_name') ? ' is-danger' : '' }}"
                            placeholder="{{ __('labels.register.first_name') }}"
                            value="{{ old('first_name', $user->first_name) }}"
                            autofocus
                        >
                    </div>

                    <p class="help{{ $errors->has('first_name') ? ' is-danger' : '' }}">{{ $errors->first('first_name') }}</p>
                </div>
            </div>

            <div class="column">
                <div class="field is-required">
                    <label class="label" for="last_name">{{ __('labels.register.last_name') }}</label>

                    <div class="control">
                        <input
                            id="last_name"
                            type="text"
                            name="last_name"
                            class="input{{ $errors->has('last_name') ? ' is-danger' : '' }}"
                            placeholder="{{ __('labels.register.last_name') }}"
                            value="{{ old('last_name', $user->last_name) }}"
                        >
                    </div>

                    <p class="help{{ $errors->has('last_name') ? ' is-danger' : '' }}">{{ $errors->first('last_name') }}</p>
                </div>
            </div>
        </div>

        <div class="field">
            <label class="label" for="institution">{{ __('labels.register.institution') }}</label>

            <div class="control">
                <input
                    id="institution"
                    type="text"
                    name="institution"
                    class="input{{ $errors->has('institution') ? ' is-danger' : '' }}"
                    placeholder="{{ __('labels.register.institution') }}"
                    value="{{ old('institution', $user->institution) }}"
                >
            </div>

            <p class="help{{ $errors->has('institution') ? ' is-danger' : '' }}">{{ $errors->first('institution') }}</p>
        </div>

        <div class="field mt-8">
            <button type="submit" class="button is-primary">{{ __('buttons.save') }}</button>
        </div>
    </form>
</div>
@endsection

@section('breadcrumbs')
<div class="breadcrumb" aria-label="breadcrumbs">
    <ul>
        <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
        <li class="is-active"><a>{{ __('navigation.preferences.index') }}</a></li>
        <li class="is-active"><a>{{ __('navigation.preferences.general_preferences') }}</a></li>
    </ul>
</div>
@endsection
