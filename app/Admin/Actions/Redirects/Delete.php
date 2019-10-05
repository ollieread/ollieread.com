<?php

namespace Ollieread\Admin\Actions\Redirects;

use Illuminate\Http\Request;
use Ollieread\Core\Operations\DeleteRedirect;
use Ollieread\Core\Operations\GetRedirect;
use Ollieread\Core\Support\Action;
use Ollieread\Core\Support\Alerts;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Delete extends Action
{
    public function delete(int $id)
    {
        $redirect = (new GetRedirect)
            ->setId($id)
            ->perform();

        if ($redirect) {
            return $this->response()->view('admin.redirects.delete', compact('redirect'));
        }

        throw new NotFoundHttpException;
    }

    public function destroy(int $id, Request $request)
    {
        $redirect = (new GetRedirect)
            ->setId($id)
            ->perform();

        if ($redirect) {
            if ((new DeleteRedirect)->setRedirect($redirect)->perform()) {
                Alerts::success(trans('admin.delete.success', ['entity' => trans_choice('entities.redirect', 1)]), 'admin.redirects');

                return $this->response()->redirectToRoute('admin:redirect.index');
            }

            Alerts::error(trans('error.unexpected'), 'admin.redirects');

            return $this->response()->redirectToRoute('admin:redirect.delete', $redirect->id);
        }

        throw new NotFoundHttpException;
    }
}
