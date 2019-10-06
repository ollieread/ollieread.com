<?php

namespace Ollieread\Articles\Actions;

use Illuminate\Http\Request;
use Ollieread\Articles\Operations\GetArticles;
use Ollieread\Core\Support\Action;
use Ollieread\Core\Support\Status;

class Upcoming extends Action
{
    public function __invoke(Request $request)
    {
        $articles = (new GetArticles)
            ->setActiveOnly(true)
            ->setStatuses(Status::DRAFT, Status::REVIEWING, Status::PUBLIC)
            ->setNotReleased(true)
            ->setPaginate(true)
            ->setLimit(20)
            ->perform();

        return $this->response()->view('articles.upcoming', compact('articles'));
    }
}
