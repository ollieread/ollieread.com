<?php

namespace Ollieread\Users\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\URL;
use Ollieread\Users\Models\User;

class Welcome extends Mailable
{
    /**
     * @var \Ollieread\Users\Models\User
     */
    private $user;

    /**
     * Create a new message instance.
     *
     * @param \Ollieread\Users\Models\User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): self
    {
        return $this->markdown('mail.users.welcome')
            ->to($this->user->email, $this->user->name ?? $this->user->username)
            ->subject(trans('mail.welcome.subject'))
            ->with([
                'user' => $this->user,
                'link' => URL::signedRoute('users:verify', [$this->user->username]),
            ]);
    }
}
