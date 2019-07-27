<?php

namespace Ollieread\Users\Observers;

use Illuminate\Contracts\Mail\Mailer;
use Ollieread\Users\Mail\Welcome;
use Ollieread\Users\Models\User;

class UserObserver
{
    /**
     * @var \Illuminate\Contracts\Mail\Mailer
     */
    private $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Handle the user "created" event.
     *
     * @param \Ollieread\Users\Models\User $user
     *
     * @return void
     */
    public function created(User $user): void
    {
        $this->mailer->send(new Welcome($user));
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param \Ollieread\Users\Models\User $user
     *
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param \Ollieread\Users\Models\User $user
     *
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }

    /**
     * Handle the user "restored" event.
     *
     * @param \Ollieread\Users\Models\User $user
     *
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the user "updated" event.
     *
     * @param \Ollieread\Users\Models\User $user
     *
     * @return void
     */
    public function updated(User $user)
    {
        //
    }
}
