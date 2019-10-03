<?php

namespace Ollieread\Articles\Actions;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Ollieread\Articles\Operations\GetArticles;
use Ollieread\Articles\Operations\GetCategories;
use Ollieread\Articles\Operations\GetOrCreateTags;
use Ollieread\Articles\Operations\GetTopics;
use Ollieread\Articles\Operations\GetVersions;
use Ollieread\Core\Support\Action;

class Filter extends Action
{
    public function __invoke(Request $request, ?string $filterString = null)
    {
        if (! $filterString) {
            return $this->createFilterAndRedirect($request);
        }

        return $this->filterAndGetResults($filterString);
    }

    private function createFilterAndRedirect(Request $request): RedirectResponse
    {
        $input = $request->only([
            'topics',
            'categories',
            'versions',
            'tags',
            'before',
            'after',
            'between',
        ]);

        $uriSegment = '';

        if ($input['topics']) {
            $uriSegment .= 'topics:' . implode(',', $input['topics']) . ';';
        }

        if ($input['categories']) {
            $uriSegment .= 'categories:' . implode(',', $input['categories']) . ';';
        }

        if ($input['versions']) {
            $uriSegment .= 'versions:' . implode(',', $input['versions']) . ';';
        }

        if ($input['tags']) {
            $uriSegment .= 'tags:' . implode(',', $input['tags']) . ';';
        }

        if ($input['before']) {
            $uriSegment .= 'before:' . $input['before'] . ';';
        }

        if ($input['after']) {
            $uriSegment .= 'after:' . $input['after'] . ';';
        }

        if ($input['between']) {
            $uriSegment .= 'between:' . implode(',', $input['between']) . ';';
        }

        return $this->response()->redirectToRoute('articles:search', substr($uriSegment, -1));
    }

    private function filterAndGetResults(string $filterString): Response
    {
        $filterParts  = explode(';', $filterString);
        $filterParsed = [];

        foreach ($filterParts as $part) {
            $subparts                   = explode(':', $part);
            $filterParsed[$subparts[0]] = explode(',', $subparts[1]);
        }

        $categories = $topics = $versions = $tags = null;

        if (isset($filterParsed['categories'])) {
            $categories = (new GetCategories)->setSlugs($filterParsed['categories'])->perform();
        }

        if (isset($filterParsed['topics'])) {
            $topics = (new GetTopics)->setSlugs($filterParsed['topics'])->perform();
        }

        if (isset($filterParsed['versions'])) {
            $versions = (new GetVersions)->setSlugs($filterParsed['versions'])->perform();
        }

        if (isset($filterParsed['tags'])) {
            $tags = (new GetOrCreateTags)->setNames($filterParsed['tags'])->perform();
        }

        $articles = (new GetArticles)
            ->setTopics($topics)
            ->setTags($tags)
            ->setVersions($versions)
            ->setCategories($categories)
            ->perform();

        $filters = compact('categories', 'topics', 'versions', 'tags');

        return $this->response()->view('articles.listing', compact('articles', 'filters'));
    }
}
