<?php

namespace Ollieread\Users\Actions\Account;

use Illuminate\Auth\SessionGuard;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Ollieread\Core\Support\Action;
use Ollieread\Core\Support\Alerts;
use Ollieread\Users\Operations\UpdateUser;

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

    public function edit(): Response
    {
        $user = $this->auth->user();

        return $this->response()->view('users.account.details', compact('user'));
    }

    public function update(Request $request)
    {
        $user = $this->auth->user();

        $input = $request->only([
            'name',
            'email',
            'current_password',
            'interests',
        ]);

        if ((new UpdateUser)->setUser($user)->setInput($input)->perform()) {
            // Todo: Implement handling of email change
            Alerts::success(trans('users.details.success'), 'account');

            return $this->response()->redirectToRoute('users:account.details.edit');
        }

        Alerts::error(trans('errors.unexpected'), 'account');

        return $this->back();
    }
}
