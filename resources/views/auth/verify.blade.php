@extends('layouts.main')

@section('content')
    <section class="hero min-h-screen bg-light is-bold">
        <div class="hero-body">
            <div class="container">
                <h1 class="title">{{ __('Verify Your Email Address') }}</h1>

                <div class="box border-t-4 border-primary">
                    @if (session('resent'))
                        <div class="message is-success" role="alert">
                            <div class="message-body">{{ __('A fresh verification link has been sent to your email address.') }}</div>
                        </div>
                    @endif

                    {{ __('Before proceeding, please check your email for a verification link.') }}
                    {{ __('If you did not receive the email') }}, <a href="{{ route('verification.resend') }}">{{ __('click here to request another') }}</a>.
                </div>
            </div>
        </div>
    </section>
@endsection
