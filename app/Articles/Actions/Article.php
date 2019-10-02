<?php

namespace Ollieread\Articles\Actions;

use Illuminate\Auth\SessionGuard;
use Ollieread\Articles\Operations\GetArticle;
use Ollieread\Articles\Operations\GetSeriesBySlug;
use Ollieread\Core\Support\Action;
use Ollieread\Core\Support\Status;
use Ollieread\Users\Support\Permissions;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Article extends Action
{
    /**
     * @var \Illuminate\Auth\SessionGuard
     */
    private $auth;

    public function __construct(SessionGuard $auth)
    {
        $this->auth = $auth;
    }

    public function __invoke(string $slug, ?string $articleSlug = null)
    {
        /**
         * @var null|\Ollieread\Users\Models\User
         */
        $user   = $this->auth->user();
        $series = null;

        if ($articleSlug) {
            $series = (new GetSeriesBySlug)
                ->setSlug($slug)
                ->perform();

            if (! $series) {
                throw new NotFoundHttpException;
            }
        }

        $statuses  = [Status::PUBLIC];
        $operation = (new GetArticle)
            ->setSlug($articleSlug ?? $slug);

        if ($user && $user->can(Permissions::ADMIN_ARTICLES)) {
            $statuses = [Status::DRAFT, Status::PRIVATE, Status::REVIEWING];
        } else {
            $operation->setActiveOnly(true);
        }

        $article = $operation
            ->setStatuses(...$statuses)
            ->perform();

        if ($article) {
            if ($article->series && ! $series && ! $article->series->is($series)) {
                return $this->response()->redirectToRoute('articles:series.article', [$article->series->slug, $article->slug]);
            }

            return $this->response()->view('articles.view', compact('article'));
        }

        throw new NotFoundHttpException;
    }
}
