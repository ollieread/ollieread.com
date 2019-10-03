<?php

namespace Ollieread\Admin\Actions\Versions;

use Illuminate\Http\Request;
use Ollieread\Articles\Operations\DeleteVersion;
use Ollieread\Articles\Operations\GetVersion;
use Ollieread\Core\Support\Action;
use Ollieread\Core\Support\Alerts;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Delete extends Action
{
    public function delete(int $id)
    {
        $version = (new GetVersion())
            ->setId($id)
            ->perform();

        if ($version) {
            return $this->response()->view('admin.versions.delete', compact('version'));
        }

        throw new NotFoundHttpException;
    }

    public function destroy(int $id, Request $request)
    {
        $version = (new GetVersion())
            ->setId($id)
            ->perform();

        if ($version) {
            $input = $request->only(['password']);

            if ((new DeleteVersion)->setInput($input)->setVersion($version)->perform()) {
                Alerts::success(trans('admin.delete.success', ['entity' => trans_choice('entities.version', 1)]), 'admin.versions');

                return $this->response()->redirectToRoute('admin:version.index');
            }

            Alerts::error(trans('error.unexpected'), 'admin.versions');

            return $this->response()->redirectToRoute('admin:version.delete', $version->id);
        }

        throw new NotFoundHttpException;
    }
}
