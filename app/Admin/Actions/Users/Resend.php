<?php

namespace Ollieread\Admin\Actions\Users;

use Ollieread\Core\Support\Action;
use Ollieread\Core\Support\Alerts;
use Ollieread\Users\Mail\Mail;
use Ollieread\Users\Operations\GetUser;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Resend extends Action
{
    public function __invoke(int $id)
    {
        $user = (new GetUser)
            ->setId($id)
            ->perform();

        if ($user && ! $user->verified) {
            Mail::welcome($user);

            Alerts::success(trans('admin.resend.success', ['entity' => trans_choice('entities.user', 1), 'mail' => 'verification email']), 'admin.users');
            return $this->back();
        }

        throw new NotFoundHttpException;
    }
}
