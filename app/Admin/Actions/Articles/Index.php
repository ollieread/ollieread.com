<?php

namespace Ollieread\Admin\Actions\Articles;

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

        return $this->response()->view('admin.articles.index', compact('articles'));
    }
}
