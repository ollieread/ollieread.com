<?php

namespace Ollieread\Articles\Actions;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Ollieread\Articles\Operations\FilterArticles;
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
        // field:FN(value1 value2 value3),field:FN(value1 value2 value)
        $input = $request->only([
            'topics',
            'categories',
            'versions',
        ]);

        $filters = [];

        if ($input['topics']) {
            $filters[] = 'topic:IN(' . implode(' ', $input['topics']) . ')';
        }

        if ($input['categories']) {
            $filters[] = 'category:IN(' . implode(' ', $input['categories']) . ')';
        }

        if ($input['categories']) {
            $filters[] = 'version:IN(' . implode(' ', $input['versions']) . ')';
        }

        $uriSegment = urlencode(implode(',', $filters));

        return $this->response()->redirectToRoute('articles:search', substr($uriSegment, -1));
    }

    private function filterAndGetResults(string $filterString): Response
    {
        $filters = $this->parseFilters($filterString);

        if ($filters) {
            $articleFiltering = new FilterArticles;
            $fieldFiltering   = $filters['field'] ?? [];

            if (isset($fieldFiltering['categories'])) {
                $articleFiltering->setCategories($fieldFiltering['categories']['args'])->setCategoryFunc($fieldFiltering['categories']['func']);
            }

            if (isset($fieldFiltering['topics'])) {
                $articleFiltering->setTopics(['topics']['args'])->setTopicFunc($fieldFiltering['topics']['func']);
            }

            if (isset($fieldFiltering['versions'])) {
                $articleFiltering->setVersions($fieldFiltering['versions']['args'])->setVersionFunc($fieldFiltering['versions']['func']);
            }
        }

        $filters = compact('categories', 'topics', 'versions', 'tags');

        return $this->response()->view('articles.listing', compact('articles', 'filters'));
    }

    private function parseFilters(string $filterString)
    {
        $parts   = explode(',', urldecode($filterString));
        $filters = [];

        foreach ($parts as $part) {
            preg_match('/(?<field>[a-z\-0-9]*):(?<func>[A-Z]*)\((?<args>[a-z0-9\-\s]*)\)/', $part, $matches);
            $filterParts = Arr::only($matches, ['field', 'func', 'args']);

            if (count($filterParts) === 3) {
                $filterParts['args']                     = explode(' ', $filterParts['args']);
                $filters['field'][$filterParts['field']] = $filterParts;
            }
        }

        return $filters;
    }
}
