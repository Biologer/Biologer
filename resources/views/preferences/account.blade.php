@extends('layouts.dashboard', ['title' => __('navigation.preferences.account_preferences')])

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
    <form action="{{ route('preferences.account.password') }}" method="POST">
        {{ csrf_field() }}
        {{ method_field('PATCH') }}

        <div class="columns">
            <div class="column">
                <div class="field is-required">
                    <label class="label">{{ __('labels.register.password') }}</label>

                    <div class="control">
                        <input
                            type="password"
                            name="password"
                            class="input{{ $errors->has('password') ? ' is-danger' : ''}}"
                            placeholder="{{ __('labels.register.password') }}"
                        />
                    </div>

                    <p class="help{{ $errors->has('password') ? ' is-danger' : '' }}">{{ $errors->first('password') }}</p>
                </div>
            </div>
            <div class="column">
                <div class="field is-required">
                    <label class="label">{{ __('labels.register.password_confirmation') }}</label>

                    <div class="control">
                        <input
                            type="password"
                            name="password_confirmation"
                            class="input{{ $errors->has('password_confirmation') ? ' is-danger' : '' }}"
                            placeholder="{{ __('labels.register.password') }}">
                    </div>

                    <p class="help{{ $errors->has('password_confirmation') ? ' is-danger' : '' }}">{{ $errors->first('password_confirmation') }}</p>
                </div>
            </div>
        </div>

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
        <li class="is-active"><a>{{ __('navigation.preferences.index') }}</a></li>
        <li class="is-active"><a>{{ __('navigation.preferences.account_preferences') }}</a></li>
    </ul>
</div>
@endsection
