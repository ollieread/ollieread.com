<?php

namespace Ollieread\Users\Actions;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Log\Logger;
use Ollieread\Core\Support\Action;
use Ollieread\Core\Support\Alerts;
use Ollieread\Users\Mail\Mail;
use Ollieread\Users\Operations\CreateUser;

class Register extends Action
{
    /**
     * @var \Illuminate\Log\Logger
     */
    private $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function create(): Response
    {
        return $this->response()->view('users.register');
    }

    public function store(Request $request)
    {
        $input = $request->all([
            'username',
            'email',
            'password',
            'password_confirmation',
        ]);

        $user = (new CreateUser)->setInput($input)->perform();

        if ($user) {
            Mail::welcome($user);
            Alerts::success(trans('users.registration.success'), 'sign-in');

            return $this->response()->redirectToRoute('users:sign-in.create');
        }

        Alerts::error(trans('errors.unexpected'), 'register');

        return $this->back();
    }
}
