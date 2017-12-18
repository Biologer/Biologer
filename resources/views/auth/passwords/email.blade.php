@extends('layouts.main')

@section('content')
<div class="container mt-8">
    <div class="columns">
        <div class="column is-8 is-offset-2">
            <h1 class="is-size-3">
              Reset Password
            </h1>

            <div class="box">
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
@endsection
