@component('mail::message')
Dear {{ $verificationToken->user->full_name }},

We received your request to register at the {{ config('app.name') }} website.

@component('mail::button', ['url' => route('auth.verify.verify', $verificationToken)])
Verify Account
@endcomponent

If you're having trouble clicking on the button, follow the link below:
[{{ route('auth.verify.verify', $verificationToken) }}]({{ route('auth.verify.verify', $verificationToken) }})

In case you have not sent the request, ignore the message or report it to the e-mail address:
[{{ config('mail.reply_address') }}](mailto:{{ config('mail.reply_address') }})

Best regards,
{{ config('app.name') }} team
@endcomponent
