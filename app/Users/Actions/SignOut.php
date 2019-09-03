<?php

namespace Ollieread\Users\Actions;

use Illuminate\Auth\SessionGuard;
use Ollieread\Core\Support\Action;

class SignOut extends Action
{
    /**
     * @var \Illuminate\Auth\SessionGuard
     */
    private $auth;

    public function __construct(SessionGuard $guard)
    {
        $this->auth = $guard;
    }

    public function __invoke()
    {
        $this->auth->logout();

        return $this->response()->redirectToRoute('site:home');
    }
}