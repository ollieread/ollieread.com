<?php

namespace Ollieread\Articles\Actions;

use Ollieread\Articles\Operations\GetArticles;
use Ollieread\Articles\Operations\GetCategoryBySlug;
use Ollieread\Core\Support\Action;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Category extends Action
{
    public function __invoke(string $slug)
    {
        $category = (new GetCategoryBySlug)->setSlug($slug)->perform();

        if ($category) {
            $articles = (new GetArticles)
                ->setCategory($category)
                ->setActiveOnly(true)
                ->setIncludePrivate(false)
                ->perform();

            return $this->response()->view('articles.category', compact('category', 'articles'));
        }

        throw new NotFoundHttpException;
    }
}