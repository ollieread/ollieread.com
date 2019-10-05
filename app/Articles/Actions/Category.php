<?php

namespace Ollieread\Articles\Actions;

use Ollieread\Articles\Operations\GetArticles;
use Ollieread\Articles\Operations\GetCategory;
use Ollieread\Core\Support\Action;
use Ollieread\Core\Support\Status;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Category extends Action
{
    public function __invoke(string $slug)
    {
        $category = (new GetCategory)->setSlug($slug)->perform();

        if ($category) {
            $articles = (new GetArticles)
                ->setCategory($category)
                ->setActiveOnly(true)
                ->setStatuses(Status::PUBLIC)
                ->setPaginate(true)
                ->setLimit(20)
                ->perform();

            return $this->response()->view('articles.category', compact('category', 'articles'));
        }

        throw new NotFoundHttpException;
    }
}
