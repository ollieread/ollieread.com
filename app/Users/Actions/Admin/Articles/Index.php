<?php

namespace Ollieread\Users\Actions\Admin\Articles;

use Ollieread\Articles\Operations\GetArticles;
use Ollieread\Core\Support\Action;

class Index extends Action
{
    public function __invoke()
    {
        $articles = (new GetArticles)
            ->setLimit(20)
            ->setPaginate(true)
            ->perform();

        return $this->response()->view('users.admin.articles.index', compact('articles'));
    }
}
