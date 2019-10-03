<?php

namespace Ollieread\Admin\Actions\Versions;

use Illuminate\Http\Request;
use Ollieread\Articles\Operations\GetVersion;
use Ollieread\Articles\Operations\UpdateArticle;
use Ollieread\Core\Support\Action;
use Ollieread\Core\Support\Alerts;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Edit extends Action
{
    public function edit(int $id)
    {
        $version = (new GetVersion())
            ->setId($id)
            ->perform();

        if ($version) {
            return $this->response()->view('admin.versions.edit', compact('version'));
        }

        throw new NotFoundHttpException;
    }

    public function update(int $id, Request $request)
    {
        $version = (new GetVersion())
            ->setId($id)
            ->perform();

        if ($version) {
            $input = $request->only([
                'name',
                'slug',
                'description',
                'docs',
                'release_date',
            ]);

            if ((new UpdateArticle)->setArticle($version)->setInput($input)->perform()) {
                Alerts::success(trans('admin.edit.success', ['entity' => trans_choice('entities.version', 1)]), 'admin.versions');

                return $this->response()->redirectToRoute('admin:version.index');
            }

            Alerts::error(trans('error.unexpected'), 'admin.versions');

            return $this->response()->redirectToRoute('admin:version.edit', $version->id);
        }

        throw new NotFoundHttpException;
    }
}
