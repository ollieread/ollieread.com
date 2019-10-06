<?php

namespace Ollieread\Articles\Actions;

use Illuminate\Support\Collection;
use Ollieread\Articles\Operations\GetArticles;
use Ollieread\Articles\Operations\GetVersion;
use Ollieread\Core\Support\Action;
use Ollieread\Core\Support\Status;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Version extends Action
{
    public function __invoke(string $slug)
    {
        $version = (new GetVersion)->setSlug($slug)->perform();

        if ($version) {
            $articles = (new GetArticles)
                ->setVersions(new Collection([$version]))
                ->setActiveOnly(true)
                ->setStatuses(Status::PUBLIC)
                ->setPaginate(true)
                ->setLimit(20)
                ->setLiveOnly(true)
                ->perform();

            return $this->response()->view('articles.version', compact('version', 'articles'));
        }

        throw new NotFoundHttpException;
    }
}
