<?php

namespace Ollieread\Admin\Actions;

use Ollieread\Articles\Operations\GetArticles;
use Ollieread\Articles\Operations\GetRecentComments;
use Ollieread\Core\Support\Action;

class Dashboard extends Action
{
    public function __invoke()
    {
        $comments = (new GetRecentComments)->perform();
        $articles = (new GetArticles)
            ->setNotReleased(true)
            ->setLimit(10)
            ->perform();

        return $this->response()->view('admin.dashboard', compact('comments', 'articles'));
    }
}
