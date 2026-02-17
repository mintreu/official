@component('mail::message')
# {{ $purposeText ?? 'Email Verification' }}

You requested a one-time passcode to {{ $purposeText ?? 'verify your email address' }}.

@component('mail::panel')
{{ $otp }}
@endcomponent

This code expires in 10 minutes. If you did not request this, you can safely ignore this email.

Thanks,<br>
{{ $appName }}
@endcomponent
