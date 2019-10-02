<?php

namespace Ollieread\Admin\Actions\Articles;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Ollieread\Articles\Operations\DeleteArticle;
use Ollieread\Articles\Operations\GetArticle;
use Ollieread\Core\Support\Action;
use Ollieread\Core\Support\Alerts;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Delete extends Action
{
    public function delete(int $id): Response
    {
        $article = (new GetArticle)
            ->setId($id)
            ->perform();

        if ($article) {
            return $this->response()->view('admin.articles.delete', compact('article'));
        }

        throw new NotFoundHttpException;
    }

    public function destroy(int $id, Request $request): RedirectResponse
    {
        $article = (new GetArticle)
            ->setId($id)
            ->perform();

        if ($article) {
            $input = $request->only(['password']);

            if ((new DeleteArticle)->setInput($input)->setArticle($article)->perform()) {
                Alerts::success(trans('admin.delete.success', ['entity' => trans_choice('entities.article', 1)]), 'admin.articles');

                return $this->response()->redirectToRoute('admin:article.index');
            }

            Alerts::error(trans('error.unexpected'), 'admin.articles');

            return $this->response()->redirectToRoute('admin:article.delete', $article->id);
        }

        throw new NotFoundHttpException;
    }
}
