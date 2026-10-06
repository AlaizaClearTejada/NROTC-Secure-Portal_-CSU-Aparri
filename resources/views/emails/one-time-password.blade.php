<x-mail::message>
Hello {{ $recipientName }},

@if ($purpose === \App\Models\OneTimePassword::PURPOSE_PASSWORD_RESET)
Use the OTP below to reset your password.
@else
Use the OTP below to verify your email address.
@endif

<x-mail::panel>
<div style="font-size: 28px; font-weight: 700; letter-spacing: 8px; text-align: center;">{{ $code }}</div>
</x-mail::panel>

This OTP expires in {{ $expiryMinutes }} minutes. If you did not request it, you can ignore this email.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
