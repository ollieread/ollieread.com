@component('mail::message')
# Welcome

Thanks for signing up on ollieread.com.

Now all you need to do is verify your email address.

@component('mail::button', ['url' => $link])
Verify My Email
@endcomponent

If you didn't sign up at ollieread.com please ignore this email.

Thanks,<br>
Ollie
@endcomponent
