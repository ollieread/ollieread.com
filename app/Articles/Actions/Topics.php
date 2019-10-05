<?php

namespace Ollieread\Articles\Actions;

use Illuminate\Http\Request;
use Ollieread\Articles\Operations\GetTopics;
use Ollieread\Core\Support\Action;

class Topics extends Action
{
    public function __invoke(Request $request)
    {
        $topics = (new GetTopics)
            ->setWithArticleCount(true)
            ->setPaginate(true)
            ->setLimit(20)
            ->perform();

        return $this->response()->view('articles.topics', compact('topics'));
    }
}
