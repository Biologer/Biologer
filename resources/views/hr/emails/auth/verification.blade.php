@component('mail::message')
Poštovani/a {{ $verificationToken->user->full_name }},

Primili smo Vaš zahtjev za registraciju na stranicu {{ config('app.name') }}.

@component('mail::button', ['url' => route('auth.verify.verify', $verificationToken)])
Potvrdite nalog
@endcomponent

Ako imate problema s klikom na dugme, kliknite na ovu poveznicu:
[{{ route('auth.verify.verify', $verificationToken) }}]({{ route('auth.verify.verify', $verificationToken) }})

U slučaju da niste poslali zahtev za registraciju, zanemarite ovu poruku ili je prijavite na e-mail adresu:
[{{ config('mail.reply_address') }}](mailto:{{ config('mail.reply_address') }})

Srdačan pozdrav,
{{ config('app.name') }} tim
@endcomponent
