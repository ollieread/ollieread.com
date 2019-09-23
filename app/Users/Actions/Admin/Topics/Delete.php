<?php

namespace Ollieread\Users\Actions\Admin\Topics;

use Illuminate\Http\Request;
use Ollieread\Articles\Operations\DeleteTopic;
use Ollieread\Articles\Operations\GetTopic;
use Ollieread\Core\Support\Action;
use Ollieread\Core\Support\Alerts;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Delete extends Action
{
    public function delete(int $id)
    {
        $topic = (new GetTopic())
            ->setId($id)
            ->perform();

        if ($topic) {
            return $this->response()->view('users.admin.topics.delete', compact('topic'));
        }

        throw new NotFoundHttpException;
    }

    public function destroy(int $id, Request $request)
    {
        $topic = (new GetTopic())
            ->setId($id)
            ->perform();

        if ($topic) {
            $input = $request->only(['password']);

            if ((new DeleteTopic)->setInput($input)->setTopic($topic)->perform()) {
                Alerts::success(trans('users.admin.delete.success', ['entity' => trans_choice('entities.topic', 1)]), 'admin.topics');

                return $this->response()->redirectToRoute('admin:topic.index');
            }

            Alerts::error(trans('error.unexpected'), 'admin.topics');

            return $this->response()->redirectToRoute('admin:topic.edit', $topic->id);
        }

        throw new NotFoundHttpException;
    }
}
