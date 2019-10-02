<?php

namespace Ollieread\Admin\Actions\Topics;

use Illuminate\Http\Request;
use Ollieread\Articles\Operations\GetTopic;
use Ollieread\Articles\Operations\UpdateTopic;
use Ollieread\Core\Support\Action;
use Ollieread\Core\Support\Alerts;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Edit extends Action
{
    public function edit(int $id)
    {
        $topic = (new GetTopic())
            ->setId($id)
            ->perform();

        if ($topic) {
            return $this->response()->view('admin.topics.edit', compact('topic'));
        }

        throw new NotFoundHttpException;
    }

    public function update(int $id, Request $request)
    {
        $topic = (new GetTopic())
            ->setId($id)
            ->perform();

        if ($topic) {
            $input = $request->only([
                'name',
                'slug',
                'description',
                'content',
            ]);

            if ((new UpdateTopic)->setTopic($topic)->setInput($input)->perform()) {
                Alerts::success(trans('admin.edit.success', ['entity' => trans_choice('entities.topic', 1)]), 'admin.topics');

                return $this->response()->redirectToRoute('admin:topic.index');
            }

            Alerts::error(trans('error.unexpected'), 'admin.topics');

            return $this->response()->redirectToRoute('admin:topic.edit', $topic->id);
        }

        throw new NotFoundHttpException;
    }
}
