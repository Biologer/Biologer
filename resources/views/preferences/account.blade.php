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
    <section>
        <h2 class="is-size-4">{{ __('navigation.preferences.change_password') }}</h2>

        <hr>

        <form action="{{ route('preferences.account.password') }}" method="POST">
            {{ csrf_field() }}
            {{ method_field('PATCH') }}

            <div class="columns">
                <div class="column">
                    <div class="field is-required">
                        <label class="label" for="password">{{ __('labels.register.password') }}</label>

                        <div class="control">
                            <input
                                id="password"
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
                        <label class="label" for="password_confirmation">{{ __('labels.register.password_confirmation') }}</label>

                        <div class="control">
                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                class="input{{ $errors->has('password_confirmation') ? ' is-danger' : '' }}"
                                placeholder="{{ __('labels.register.password') }}">
                        </div>

                        <p class="help{{ $errors->has('password_confirmation') ? ' is-danger' : '' }}">{{ $errors->first('password_confirmation') }}</p>
                    </div>
                </div>
            </div>

            <div class="field">
                <button type="submit" class="button is-primary">{{ __('buttons.save') }}</button>
            </div>
        </form>
    </section>

    <section class="mt-8">
        <h2 class="is-size-4 has-text-danger">{{ __('navigation.preferences.delete_account') }}</h2>

        <hr>

        <nz-delete-account-button
            action="{{ route('preferences.account.delete') }}"
            csrf-token="{{ csrf_token() }}"
        />
    </section>
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
