<?php

namespace Ollieread\Articles\Actions;

use Ollieread\Articles\Operations\GetArticle;
use Ollieread\Articles\Operations\GetSeriesBySlug;
use Ollieread\Core\Support\Action;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Article extends Action
{
    public function __invoke(string $slug, ?string $articleSlug = null)
    {
        $series = null;

        if ($articleSlug) {
            $series = (new GetSeriesBySlug)
                ->setSlug($slug)
                ->perform();

            if (! $series) {
                throw new NotFoundHttpException;
            }
        }

        $article = (new GetArticle)
            ->setSlug($articleSlug ?? $slug)
            ->setActiveOnly(true)
            ->setIncludePrivate(false)
            ->perform();

        if ($article) {
            if ($article->series && ! $series && ! $article->series->is($series)) {
                return $this->response()->redirectToRoute('articles:series.article', [$article->series->slug, $article->slug]);
            }

            return $this->response()->view('articles.view', compact('article'));
        }

        throw new NotFoundHttpException;
    }
}