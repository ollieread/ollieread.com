<?php

namespace Ollieread\Admin\Actions\Articles;

use Illuminate\Http\Request;
use Ollieread\Articles\Operations\GetArticle;
use Ollieread\Articles\Operations\GetTopics;
use Ollieread\Articles\Operations\GetVersions;
use Ollieread\Articles\Operations\UpdateArticle;
use Ollieread\Core\Support\Action;
use Ollieread\Core\Support\Alerts;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Edit extends Action
{
    public function edit(int $id)
    {
        $article = (new GetArticle)
            ->setId($id)
            ->perform();

        if ($article) {
            $versions        = (new GetVersions)->perform();
            $topics          = (new GetTopics)->perform();
            $articleVersions = $article->versions;
            $articleTopics   = $article->topics;

            return $this->response()->view('admin.articles.edit', compact('article', 'versions', 'topics', 'articleVersions', 'articleTopics'));
        }

        throw new NotFoundHttpException;
    }

    public function update(int $id, Request $request)
    {
        $article = (new GetArticle())
            ->setId($id)
            ->perform();

        if ($article) {
            $input = $request->only([
                'name',
                'slug',
                'description',
                'content',
            ]);

            if ((new UpdateArticle)->setArticle($article)->setInput($input)->perform()) {
                Alerts::success(trans('admin.edit.success', ['entity' => trans_choice('entities.article', 1)]), 'admin.articles');

                return $this->response()->redirectToRoute('admin:article.index');
            }

            Alerts::error(trans('error.unexpected'), 'admin.articles');

            return $this->response()->redirectToRoute('admin:article.edit', $article->id);
        }

        throw new NotFoundHttpException;
    }
}
