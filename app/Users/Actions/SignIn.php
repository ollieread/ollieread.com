<?php

namespace Ollieread\Users\Actions;

use Illuminate\Auth\SessionGuard;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Ollieread\Core\Support\Action;
use Ollieread\Core\Support\Alerts;
use Ollieread\Users\Operations\GetUser;
use Ollieread\Users\Validators\LoginValidator;

class SignIn extends Action
{
    /**
     * @var \Illuminate\Auth\SessionGuard
     */
    private $auth;

    public function __construct(SessionGuard $auth)
    {
        $this->auth = $auth;
    }

    public function create(Request $request): Response
    {
        $this->handleRedirectAfter($request);

        return $this->response()->view('users.signin');
    }

    public function store(Request $request)
    {
        $input = $request->only([
            'email',
            'password',
        ]);

        LoginValidator::validate($input);

        $user = (new GetUser)
            ->setEmail($input['email'])
            ->perform();

        if ($user) {
            if ($this->auth->getProvider()->validateCredentials($user, $input)) {
                if (! $user->active) {
                    Alerts::error(trans('users.sign-in.inactive'), 'sign-in');
                } else if (! $user->verified) {
                    Alerts::error(trans('users.sign-in.verification'), 'sign-in');
                } else {
                    $this->auth->login($user);
                    return $this->response()->redirectToRoute('site:home');
                }
            }
        }

        Alerts::error(trans('users.sign-in.failure'), 'sign-in');
        return $this->response()->redirectToRoute('users:sign-in.create');
    }
}