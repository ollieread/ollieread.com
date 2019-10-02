<?php

namespace Ollieread\Admin\Actions\Articles;

use Illuminate\Http\Request;
use Ollieread\Articles\Operations\GetArticle;
use Ollieread\Core\Support\Action;
use Ollieread\Core\Support\Alerts;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Toggle extends Action
{
    public function __invoke(int $id, Request $request)
    {
        $this->handleRedirectAfter($request);

        $article = (new GetArticle)
            ->setId($id)
            ->perform();

        if ($article) {
            $article->active = ! $article->active;

            if ($article->save()) {
                Alerts::success(trans('admin.toggle.success', [
                    'action' => $article->active ? 'enabled' : 'disabled',
                    'entity' => trans_choice('entities.article', 1),
                ]), 'admin.articles');

                return $this->back();
            }

            Alerts::error(trans('error.unexpected'), 'admin.articles');

            return $this->redirect('admin:article.index');
        }

        throw new NotFoundHttpException;
    }
}
