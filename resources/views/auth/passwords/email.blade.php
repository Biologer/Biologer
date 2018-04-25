@extends('layouts.main', ['title' => __('navigation.reset_password')])

@section('content')
<section class="hero min-h-screen bg-light is-bold">
    <div class="hero-body">
        <div class="container">
            <div class="columns">
                <div class="column is-8 is-offset-2">
                    <h1 class="title">{{ __('navigation.reset_password') }}</h1>

                    <div class="box border-t-4 border-primary">
                        @if (session('status'))
                            <article class="message is-success">
                                <div class="message-body">
                                    {{ session('status') }}
                                </div>
                            </article>
                       @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            {{ csrf_field() }}

                            <div class="field is-required">
                                <label class="label">{{ __('labels.forgot_password.email') }}</label>

                                <div class="control">
                                    <input type="email"
                                        name="email"
                                        class="input{{ $errors->has('email') ? ' is-danger' : '' }}"
                                        placeholder="{{ __('labels.forgot_password.email') }}"
                                        value="{{ old('email') }}"
                                        required
                                        autofocus>
                                </div>

                                <p class="help{{ $errors->has('email') ? ' is-danger' : '' }}">{{ $errors->first('email') }}</p>
                            </div>

                            <div class="field">
                                <button type="submit" class="button is-primary">
                                    {{ __('buttons.send_password_reset_link') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
