<?php

namespace Ollieread\Articles;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\Factory;
use Ollieread\Articles\Composers\CategorySidebarComposer;
use Ollieread\Articles\Composers\SeriesSidebarComposer;
use Ollieread\Articles\Models\Article;
use Ollieread\Articles\Models\Comment;
use Ollieread\Articles\Observers\ArticleObserver;
use Ollieread\Articles\Observers\CommentObserver;
use Ollieread\Articles\Routes\ArticleRoutes;
use Ollieread\Core\Support\Routes;

class ArticleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $viewFactory = $this->app->make(Factory::class);

        $viewFactory->composer('articles.partials.sidebar.categories', CategorySidebarComposer::class);
        $viewFactory->composer('articles.partials.sidebar.series', SeriesSidebarComposer::class);

        Article::observe(ArticleObserver::class);
        Comment::observe(CommentObserver::class);
    }

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function register(): void
    {
        $routes = $this->app->make(Routes::class);

        if ($routes) {
            $routes->addWebRoutes(ArticleRoutes::class);
        }
    }
}
