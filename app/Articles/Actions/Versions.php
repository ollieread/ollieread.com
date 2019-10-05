<?php

namespace Ollieread\Articles\Actions;

use Illuminate\Http\Request;
use Ollieread\Articles\Operations\GetArticles;
use Ollieread\Articles\Operations\GetVersions;
use Ollieread\Core\Support\Action;
use Ollieread\Core\Support\Status;

class Versions extends Action
{
    public function __invoke(Request $request)
    {
        $versions = (new GetVersions)
            ->setWithArticleCount(true)
            ->setUsedOnly(true)
            ->setPaginate(true)
            ->setLimit(20)
            ->perform();

        return $this->response()->view('articles.versions', compact('versions'));
    }
}
