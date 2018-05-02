@component('mail::message')
Poštovani/a {{ $verificationToken->user->full_name }},

Primili smo Vaš zahtev za registraciju na sajt {{ config('app.name') }}.

@component('mail::button', ['url' => route('auth.verify.verify', $verificationToken)])
Potvrdite nalog
@endcomponent

Ako imate problema sa klikom na dugme idite na ovu veb adresu:
[{{ route('auth.verify.verify', $verificationToken) }}]({{ route('auth.verify.verify', $verificationToken) }})

U slučaju da niste poslali zahtev za registraciju, ignorišite ovu poruku ili je prijavite na e-mail adresu:
[{{ config('mail.reply_address') }}](mailto:{{ config('mail.reply_address') }})

Srdačan pozdrav,
{{ config('app.name') }} tim
@endcomponent
