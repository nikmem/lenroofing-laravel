@component('mail::message')
# Reset Your Password

Click the button below to reset your password:

@component('mail::button', ['url' => $body['url']])
Reset Password
@endcomponent

If you did not request a password reset, you can safely ignore this email.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
