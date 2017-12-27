Dear {{ $verificationToken->user->full_name }},

We received your request to register at the Biologer website. To continue with registration follow the link below:
<a href="{{ route('auth.verify.verify', $verificationToken) }}">{{ route('auth.verify.verify', $verificationToken) }}</a>

In case you have not sent the request, ignore the message or report it to the e-mail address:
<a href="mailto:info@biologer.org">info@biologer.org</a>

Best regards,
Biologer team
