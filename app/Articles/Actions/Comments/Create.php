<?php

namespace Ollieread\Articles\Actions\Comments;

use Illuminate\Http\Request;
use Ollieread\Articles\Operations\CreateArticleComment;
use Ollieread\Articles\Operations\GetArticle;
use Ollieread\Articles\Operations\GetArticleComment;
use Ollieread\Articles\Transformers\CommentTransformer;
use Ollieread\Core\Support\Action;
use Ollieread\Core\Support\Status;
use Ollieread\Users\Models\User;

class Create extends Action
{
    public function __invoke(string $articleSlug, Request $request, User $user)
    {
        $article = (new GetArticle)
            ->setSlug($articleSlug)
            ->setStatuses(Status::PUBLIC)
            ->setActiveOnly(true)
            ->perform();

        if ($article) {
            $input  = $request->only(['comment', 'parent']);
            $parent = null;

            if ($input['parent']) {
                $parent = (new GetArticleComment)->setId($input['parent'])->perform();

                if (! $parent) {
                    return $this->response()->json(['message' => 'Invalid parent comment'], 400);
                }
            }

            $comment = (new CreateArticleComment)
                ->setComment($input['comment'])
                ->setArticle($article)
                ->setParent($parent)
                ->setAuthor($user)
                ->perform();

            if ($comment) {
                return $this->transform($comment, CommentTransformer::class);
            }

            return $this->response()->json(['message' => 'Unexpected error'], 500);
        }
    }
}
