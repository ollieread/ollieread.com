<?php

namespace Ollieread\Admin\Actions\Topics;

use Ollieread\Articles\Operations\GetTopics;
use Ollieread\Core\Support\Action;

class Index extends Action
{
    public function __invoke()
    {
        $topics = (new GetTopics)
            ->setLimit(20)
            ->setPaginate(true)
            ->perform();

        return $this->response()->view('admin.topics.index', compact('topics'));
    }
}
