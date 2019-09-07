<?php

namespace Ollieread\Users\Observers;

use Illuminate\Contracts\Mail\Mailer;
use Ollieread\Users\Models\User;
use Ollieread\Users\Support\Mailchimp;

class UserObserver
{
    /**
     * @var \Illuminate\Contracts\Mail\Mailer
     */
    private $mailer;

    /**
     * @var \Ollieread\Users\Support\Mailchimp
     */
    private $mailchimp;

    public function __construct(Mailer $mailer, Mailchimp $mailchimp)
    {
        $this->mailer    = $mailer;
        $this->mailchimp = $mailchimp;
    }

    /**
     * Handle the user "updating" event.
     *
     * @param \Ollieread\Users\Models\User $user
     *
     * @return void
     */
    public function updating(User $user): void
    {
        $onChanges = ['email', 'username', 'interests'];
        $changed   = true;

        foreach ($onChanges as $attribute) {
            if ($user->getAttributeValue($attribute) !== $user->getOriginal($attribute)) {
                $changed = true;
                break;
            }
        }

        if ($changed) {
            $interests = [];

            foreach (Mailchimp::INTERESTS as $interest => $id) {
                $interests[$id] = in_array($interest, $user->interests, true);
            }

            $this->mailchimp->subscribe($user->email, $user->username, $interests);
        }
    }
}
