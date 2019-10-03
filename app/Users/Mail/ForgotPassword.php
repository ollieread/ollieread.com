<?php

namespace Ollieread\Users\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\URL;
use Ollieread\Users\Models\User;

class ForgotPassword extends Mailable
{
    /**
     * @var \Ollieread\Users\Models\User
     */
    private $user;

    /**
     * @var string
     */
    private $token;

    /**
     * Create a new message instance.
     *
     * @param \Ollieread\Users\Models\User $user
     * @param string                       $token
     */
    public function __construct(User $user, string $token)
    {
        $this->user  = $user;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): self
    {
        return $this->markdown('mail.users.password.forgot')
            ->to($this->user->email, $this->user->name ?? $this->user->username)
            ->subject(trans('mail.password.forgot.subject'))
            ->with([
                'user' => $this->user,
                'link' => URL::signedRoute('users:password.reset.create', [
                    'token' => $this->token,
                    'email' => $this->user->email,
                ]),
            ]);
    }
}
