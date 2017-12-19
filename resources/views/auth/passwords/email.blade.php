@extends('layouts.main')

@section('content')
<section class="hero min-h-screen bg-light is-bold">
    <div class="hero-body">
        <div class="container">
            <div class="columns">
                <div class="column is-8 is-offset-2">
                    <h1 class="title">Reset Password</h1>

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

                            <div class="field">
                                <label class="label">Email</label>

                                <div class="control">
                                    <input type="email"
                                        name="email"
                                        class="input{{ $errors->has('email') ? ' is-danger' : '' }}"
                                        placeholder="Email"
                                        value="{{ old('email') }}"
                                        required
                                        autofocus>
                                </div>

                                <p class="help{{ $errors->has('email') ? ' is-danger' : '' }}">{{ $errors->first('email') }}</p>
                            </div>

                            <div class="field">
                                <button type="submit" class="button is-primary">
                                    Send Password Reset Link
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
