<?php

namespace Ollieread\Admin\Actions\Users;

use Ollieread\Core\Support\Action;
use Ollieread\Core\Support\Alerts;
use Ollieread\Users\Operations\GetUser;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Toggle extends Action
{
    public function __invoke(int $id)
    {
        $user = (new GetUser)
            ->setId($id)
            ->perform();

        if ($user) {
            $user->active = ! $user->active;

            if ($user->save()) {
                Alerts::success(trans('admin.toggle.success', ['action' => $user->active ? 'enabled' : 'disabled', 'entity' => trans_choice('entities.user', 1)]), 'admin.users');

                return $this->back();
            }

            Alerts::error(trans('error.unexpected'), 'admin.users');

            return $this->response()->redirectToRoute('admin:user.index');
        }

        throw new NotFoundHttpException;
    }
}
