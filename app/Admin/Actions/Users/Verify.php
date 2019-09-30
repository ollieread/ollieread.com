<?php

namespace Ollieread\Admin\Actions\Users;

use Ollieread\Core\Support\Action;
use Ollieread\Core\Support\Alerts;
use Ollieread\Users\Operations\GetUser;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Verify extends Action
{
    public function __invoke(int $id)
    {
        $user = (new GetUser)
            ->setId($id)
            ->perform();

        if ($user && ! $user->verified) {
            $user->verified = true;

            if ($user->save()) {
                Alerts::success(trans('admin.verify.success'), 'admin.users');
                return $this->back();
            }

            Alerts::error(trans('error.unexpected'), 'admin.users');
            return $this->response()->redirectToRoute('admin:user.index');
        }

        throw new NotFoundHttpException;
    }
}
