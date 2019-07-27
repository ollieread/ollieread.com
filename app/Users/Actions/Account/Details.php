<?php

namespace Ollieread\Users\Actions\Account;

use Illuminate\Auth\SessionGuard;
use Ollieread\Core\Support\Action;

class Details extends Action
{
    /**
     * @var \Illuminate\Auth\SessionGuard
     */
    private $auth;

    public function __construct(SessionGuard $auth)
    {
        $this->auth = $auth;
    }
    public function edit()
    {
        $user    = $this->auth->user();

        return $this->response()->view('users.account.details', compact('user'));
    }
}