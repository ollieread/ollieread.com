@component('mail::message')
# Welcome

Thanks for signing up on ollieread.com.

Now all you need to do is verify your email address.

@component('mail::button', ['url' => $link])
Verify My Email
@endcomponent

Thanks,<br>
Ollie
@endcomponent
