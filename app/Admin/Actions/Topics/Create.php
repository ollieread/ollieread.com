<?php

namespace Ollieread\Admin\Actions\Topics;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Ollieread\Articles\Operations\CreateTopic;
use Ollieread\Core\Support\Action;
use Ollieread\Core\Support\Alerts;

class Create extends Action
{
    public function create()
    {
        return $this->response()->view('admin.topics.create');
    }

    public function store(Request $request)
    {
        $input = $request->only([
            'name',
            'slug',
            'description',
            'content',
        ]);

        if (empty($input['slug'])) {
            $input['slug'] = Str::slug($input['name']);
        }

        if ((new CreateTopic)->setInput($input)->perform()) {
            Alerts::success(trans('admin.create.success', ['entity' => trans_choice('entities.topic', 1)]), 'admin.topics');

            return $this->response()->redirectToRoute('admin:topic.index');
        }

        Alerts::error(trans('error.unexpected'), 'admin.topics');

        return $this->response()->redirectToRoute('admin:topic.create');
    }
}
