<?php

namespace Ollieread\Users\Actions\Password;

use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Ollieread\Core\Support\Action;
use Ollieread\Core\Support\Alerts;
use Ollieread\Users\Operations\GetUser;
use Ollieread\Users\Validators\ForgotPasswordValidator;

class Forgot extends Action
{
    /**
     * @var \Illuminate\Auth\Passwords\PasswordBroker
     */
    private $password;

    public function __construct(PasswordBroker $password)
    {
        $this->password = $password;
    }

    public function create(): Response
    {
        return $this->response()->view('users.password.forgot');
    }

    public function store(Request $request): RedirectResponse
    {
        $input = $request->only([
            'email',
        ]);

        ForgotPasswordValidator::validate($input);

        $user = (new GetUser)
            ->setEmail($input['email'])
            ->perform();

        if (! $user) {
            Alerts::error(trans('users.password.forgot.unknown'), 'password');
            return $this->response()->redirectToRoute('users:password.forgot.create');
        }

        if (! $user->active) {
            Alerts::error(trans('users.password.forgot.inactive'), 'password');
            return $this->response()->redirectToRoute('users:password.forgot.create');
        }

        if (! $user->verified) {
            Alerts::error(trans('users.password.forgot.unverified'), 'password');
            return $this->response()->redirectToRoute('users:password.forgot.create');
        }

        if ($this->password->sendResetLink($input) === PasswordBroker::RESET_LINK_SENT) {
            Alerts::success(trans('users.password.forgot.success'), 'password');
            return $this->response()->redirectToRoute('users:password.forgot.create');
        }

        Alerts::error(trans('error.unexpected'), 'password');
        return $this->response()->redirectToRoute('users:password.forgot.create');
    }
}
