<?php

namespace Ollieread\Articles\Actions;

use Illuminate\Http\Request;
use Ollieread\Articles\Operations\GetArticles;
use Ollieread\Core\Support\Action;
use Ollieread\Core\Support\Status;

class Schedule extends Action
{
    public function __invoke(Request $request)
    {
        $articles = (new GetArticles)
            ->setActiveOnly(true)
            ->setStatuses(Status::PUBLIC)
            ->setNotReleased(true)
            ->setPaginate(true)
            ->setLimit(20)
            ->perform();

        return $this->response()->view('articles.schedule', compact('articles'));
    }
}
