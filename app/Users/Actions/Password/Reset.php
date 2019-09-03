<?php

namespace Ollieread\Users\Actions\Password;

use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Ollieread\Core\Support\Action;
use Ollieread\Core\Support\Alerts;
use Ollieread\Users\Models\User;
use Ollieread\Users\Validators\ResetPasswordValidator;

class Reset extends Action
{
    /**
     * @var \Illuminate\Auth\Passwords\PasswordBroker
     */
    private $password;

    public function __construct(PasswordBroker $password)
    {
        $this->password = $password;
    }

    public function create(string $token): Response
    {
        return $this->response()->view('users.password.reset', compact('token'));
    }

    public function store(Request $request): RedirectResponse
    {
        $input = $request->only([
            'email',
            'token',
            'password',
            'password_confirmation',
        ]);

        ResetPasswordValidator::validate($input);

        $result = $this->password->reset($input, static function (User $user, string $password) {
            $user->password = $password;
            $user->save();
        });

        if ($result === PasswordBroker::PASSWORD_RESET) {
            Alerts::success(trans('users.password.reset.success'), 'sign-in');
            return $this->response()->redirectToRoute('users:sign-in.create');
        }

        switch($result) {
            case PasswordBroker::INVALID_TOKEN:
                $message = trans('users.password.reset.token');
                break;
            case PasswordBroker::INVALID_USER:
                $message = trans('users.password.reset.user');
                break;
            default:
                $message = trans('error.unexpected');
                break;
        }

        Alerts::error($message, 'password');
        return $this->response()->redirectToRoute('users:password.reset.create', $input['token']);
    }
}
