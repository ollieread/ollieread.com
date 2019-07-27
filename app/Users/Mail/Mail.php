<?php

namespace Ollieread\Users\Mail;

use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Mail\Mailer;
use Ollieread\Users\Models\User;

class Mail
{
    protected static function mailer(): Mailer
    {
        try {
            return Container::getInstance()->make(Mailer::class);
        } catch (BindingResolutionException $e) {
            report($e);
        }
    }

    public static function welcome(User $user): void
    {
        self::mailer()->send(new Welcome($user));
    }
}