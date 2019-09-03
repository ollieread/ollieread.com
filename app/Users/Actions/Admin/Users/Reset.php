<?php

namespace Ollieread\Users\Actions\Admin\Users;

use Illuminate\Contracts\Auth\PasswordBroker;
use Ollieread\Core\Support\Action;
use Ollieread\Core\Support\Alerts;
use Ollieread\Users\Operations\GetUser;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Reset extends Action
{
    /**
     * @var \Illuminate\Contracts\Auth\PasswordBroker
     */
    private $password;

    public function __construct(PasswordBroker $password)
    {
        $this->password = $password;
    }

    public function __invoke(int $id)
    {
        $user = (new GetUser)
            ->setId($id)
            ->perform();

        if ($user) {
            if ($this->password->sendResetLink(['email' => $user->email]) === PasswordBroker::RESET_LINK_SENT) {
                Alerts::success(trans('users.admin.reset.success'), 'admin.users');
                return $this->back();
            }

            Alerts::error(trans('error.unexpected'), 'admin.users');
            return $this->response()->redirectToRoute('admin:user.index');
        }

        throw new NotFoundHttpException;
    }
}
