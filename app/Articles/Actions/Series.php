<?php

namespace Ollieread\Articles\Actions;

use Ollieread\Articles\Operations\GetArticles;
use Ollieread\Articles\Operations\GetSeriesBySlug;
use Ollieread\Core\Support\Action;
use Ollieread\Core\Support\Status;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Series extends Action
{
    public function __invoke(string $slug)
    {
        $series = (new GetSeriesBySlug)
            ->setSlug($slug)
            ->perform();

        if ($series) {
            $articles = (new GetArticles)
                ->setActiveOnly(true)
                ->setStatuses(Status::PUBLIC)
                ->setSeries($series)
                ->setLiveOnly(true)
                ->perform();

            return $this->response()->view('articles.series', compact('series', 'articles'));
        }

        throw new NotFoundHttpException;
    }
}
