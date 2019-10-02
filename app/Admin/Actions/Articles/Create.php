<?php

namespace Ollieread\Admin\Actions\Articles;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Ollieread\Articles\Operations\CreateArticle;
use Ollieread\Articles\Operations\GetCategories;
use Ollieread\Articles\Operations\GetTopics;
use Ollieread\Articles\Operations\GetVersions;
use Ollieread\Core\Support\Action;
use Ollieread\Core\Support\Alerts;

class Create extends Action
{
    public function create()
    {
        $categories = (new GetCategories)->perform();
        $versions   = (new GetVersions)->perform();
        $topics     = (new GetTopics)->perform();

        return $this->response()->view('admin.articles.create', compact('categories', 'versions', 'topics'));
    }

    public function store(Request $request)
    {
        $input = $request->all([
            'name',
            'title',
            'heading',
            'seo_description',
            'slug',
            'excerpt',
            'content',
            'active',
            'status',
            'post_at',
            'category',
            'parent',
            'series',
            'tags',
            'topics',
            'versions',
        ]);

        $input['active'] = (bool) $input['active'];

        if (empty($input['slug'])) {
            $input['slug'] = Str::slug($input['name']);
        }

        if ((new CreateArticle)->setInput($input)->perform()) {
            Alerts::success(trans('admin.create.success', ['entity' => trans_choice('entities.article', 1)]), 'admin.articles');

            return $this->response()->redirectToRoute('admin:article.index');
        }

        Alerts::error(trans('error.unexpected'), 'admin.articles');

        return $this->response()->redirectToRoute('admin:article.create');
    }
}
