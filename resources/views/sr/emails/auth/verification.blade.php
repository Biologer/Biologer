@component('mail::message')
Поштовани/а {{ $verificationToken->user->full_name }},

Примили смо Ваш захтев за регистрацију на сајт {{ config('app.name') }}.

@component('mail::button', ['url' => route('auth.verify.verify', $verificationToken)])
Потврдите налог
@endcomponent

Ако имате проблема са кликом на дугме идите на ву веб адресу:
[{{ route('auth.verify.verify', $verificationToken) }}]({{ route('auth.verify.verify', $verificationToken) }})

У случају да нисте послали захтев за регистрацију, игноришите ову поруку или је пријавите на e-mail адресу:
[{{ config('mail.reply_address') }}](mailto:{{ config('mail.reply_address') }})

Срдачан поздрав,
{{ config('app.name') }} тим
@endcomponent
