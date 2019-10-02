<?php

namespace Ollieread\Admin\Actions\Users;

use Illuminate\Http\Request;
use Ollieread\Core\Support\Action;
use Ollieread\Core\Support\Alerts;
use Ollieread\Users\Operations\GetRoles;
use Ollieread\Users\Operations\GetUser;
use Ollieread\Users\Operations\UpdateUser;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Edit extends Action
{
    public function edit(int $id)
    {
        $user = (new GetUser)
            ->setId($id)
            ->perform();

        if ($user) {
            $roles     = (new GetRoles)->perform();
            $userRoles = $user->roles->pluck('id');

            return $this->response()->view('admin.users.edit', compact('user', 'roles', 'userRoles'));
        }

        throw new NotFoundHttpException;
    }

    public function update(int $id, Request $request)
    {
        $user = (new GetUser)
            ->setId($id)
            ->perform();

        if ($user) {
            $input = $request->only([
                'name',
                'username',
                'email',
                'roles',
            ]);

            if ((new UpdateUser)->setUser($user)->setInput($input)->setAdmin(true)->perform()) {
                Alerts::success(trans('admin.edit.success', ['entity' => trans_choice('entities.user', 1)]), 'admin.users');

                return $this->response()->redirectToRoute('admin:user.index');
            }

            Alerts::error(trans('error.unexpected'), 'admin.users');

            return $this->response()->redirectToRoute('admin:user.edit', $user->id);
        }

        throw new NotFoundHttpException;
    }
}
