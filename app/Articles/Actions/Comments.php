<?php

namespace Ollieread\Articles\Actions;

use Ollieread\Articles\Operations\GetArticle;
use Ollieread\Articles\Operations\GetArticleComments;
use Ollieread\Articles\Transformers\CommentTransformer;
use Ollieread\Core\Support\Action;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Comments extends Action
{
    public function __invoke(string $slug)
    {
        $article = (new GetArticle)
            ->setSlug($slug)
            ->setActiveOnly(true)
            ->setIncludePrivate(false)
            ->perform();

        if ($article) {
            $comments = (new GetArticleComments)
                ->setHierarchy(true)
                ->setArticle($article)
                ->perform();

            return $this->transform($comments, CommentTransformer::class);
        }

        throw new NotFoundHttpException;
    }
}