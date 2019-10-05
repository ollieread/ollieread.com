<?php

namespace Ollieread\Articles\Actions;

use Illuminate\Support\Collection;
use Ollieread\Articles\Operations\GetArticles;
use Ollieread\Articles\Operations\GetTopic;
use Ollieread\Core\Support\Action;
use Ollieread\Core\Support\Status;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Topic extends Action
{
    public function __invoke(string $slug)
    {
        $topic = (new GetTopic)->setSlug($slug)->perform();

        if ($topic) {
            $articles = (new GetArticles)
                ->setTopics(new Collection([$topic]))
                ->setActiveOnly(true)
                ->setStatuses(Status::PUBLIC)
                ->perform();

            return $this->response()->view('articles.topic', compact('topic', 'articles'));
        }

        throw new NotFoundHttpException;
    }
}
