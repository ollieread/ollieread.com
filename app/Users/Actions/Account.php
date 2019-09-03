<?php

namespace Ollieread\Users\Actions;

use Illuminate\Auth\SessionGuard;
use Ollieread\Core\Support\Action;

class Account extends Action
{
    /**
     * @var \Illuminate\Auth\SessionGuard
     */
    private $auth;

    public function __construct(SessionGuard $auth)
    {
        $this->auth = $auth;
    }

    public function get()
    {
        $user    = $this->auth->user();
        $socials = $user->social->keyBy('provider');

        return $this->response()->view('users.account', compact('user', 'socials'));
    }
}