<?php

namespace Ollieread\Users\Actions\Account;

use Illuminate\Auth\SessionGuard;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Ollieread\Core\Support\Action;
use Ollieread\Core\Support\Alerts;
use Ollieread\Users\Operations\UpdateUserPassword;

class Password extends Action
{
    /**
     * @var \Illuminate\Auth\SessionGuard
     */
    private $auth;

    public function __construct(SessionGuard $auth)
    {
        $this->auth = $auth;
    }

    public function edit(): Response
    {
        $user = $this->auth->user();

        return $this->response()->view('users.account.password', compact('user'));
    }

    public function update(Request $request)
    {
        $user = $this->auth->user();

        $input = $request->only([
            'password',
            'password_confirmation',
            'current_password',
        ]);

        if ((new UpdateUserPassword)->setUser($user)->setInput($input)->perform()) {
            // Todo: Email based on password change
            Alerts::success(trans('users.password.success'), 'account');

            return $this->response()->redirectToRoute('users:account.password.edit');
        }

        Alerts::error(trans('errors.unexpected'), 'account');

        return $this->back();
    }
}
