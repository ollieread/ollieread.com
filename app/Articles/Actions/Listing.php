<?php

namespace Ollieread\Articles\Actions;

use Illuminate\Http\Request;
use Ollieread\Articles\Operations\GetArticles;
use Ollieread\Core\Support\Action;

class Listing extends Action
{
    public function __invoke(Request $request)
    {
        $articles = (new GetArticles)
            ->setActiveOnly(true)
            ->setIncludePrivate(false)
            ->perform();

        return $this->response()->view('articles.listing', compact('articles'));
    }
}