<?php

namespace Ollieread\Admin\Actions\Redirects;

use Illuminate\Http\Request;
use Ollieread\Core\Operations\GetRedirect;
use Ollieread\Core\Operations\UpdateRedirect;
use Ollieread\Core\Support\Action;
use Ollieread\Core\Support\Alerts;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Edit extends Action
{
    public function edit(int $id)
    {
        $redirect = (new GetRedirect)
            ->setId($id)
            ->perform();

        if ($redirect) {
            return $this->response()->view('admin.redirects.edit', compact('redirect'));
        }

        throw new NotFoundHttpException;
    }

    public function update(int $id, Request $request)
    {
        $redirect = (new GetRedirect)
            ->setId($id)
            ->perform();

        if ($redirect) {
            $input = $request->only([
                'from',
                'to'
            ]);

            if ((new UpdateRedirect)->setRedirect($redirect)->setInput($input)->perform()) {
                Alerts::success(trans('admin.edit.success', ['entity' => trans_choice('entities.redirect', 1)]), 'admin.redirects');

                return $this->response()->redirectToRoute('admin:redirect.index');
            }

            Alerts::error(trans('error.unexpected'), 'admin.redirects');

            return $this->response()->redirectToRoute('admin:redirect.edit', $redirect->id);
        }

        throw new NotFoundHttpException;
    }
}
