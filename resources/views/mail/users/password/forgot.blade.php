@component('mail::message')
# Forgot Password

A password reset was requested.

If you made the request please click the button below to continue, otherwise you can ignore this email

@component('mail::button', ['url' => $link])
Reset My Password
@endcomponent

Thanks,<br>
Ollie
@endcomponent
