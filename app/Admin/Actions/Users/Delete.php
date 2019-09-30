<?php

namespace Ollieread\Admin\Actions\Users;

use Illuminate\Http\Request;
use Ollieread\Core\Support\Action;
use Ollieread\Core\Support\Alerts;
use Ollieread\Users\Operations\DeleteUser;
use Ollieread\Users\Operations\GetUser;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Delete extends Action
{
    public function delete(int $id)
    {
        $user = (new GetUser)
            ->setId($id)
            ->perform();

        if ($user) {
            return $this->response()->view('admin.users.delete', compact('user'));
        }

        throw new NotFoundHttpException;
    }

    public function destroy(int $id, Request $request)
    {
        $user = (new GetUser)
            ->setId($id)
            ->perform();

        if ($user) {
            $input = $request->only(['password']);

            if ((new DeleteUser)->setInput($input)->setUser($user)->setCascade((bool) ($request->input('cascade', false)))->perform()) {
                Alerts::success(trans('admin.delete.success', ['entity' => trans_choice('entities.user', 1)]), 'admin.users');

                return $this->response()->redirectToRoute('admin:user.index');
            }

            Alerts::error(trans('error.unexpected'), 'admin.users');

            return $this->response()->redirectToRoute('admin:user.delete', $user->id);
        }

        throw new NotFoundHttpException;
    }
}
