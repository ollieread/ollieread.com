<?php

namespace Ollieread\Articles\Observers;

use Ollieread\Articles\Models\Article;
use Ollieread\Core\Services\Ids;
use Ollieread\Core\Services\Redirects;

class ArticleObserver
{
    /**
     * Handle the post "created" event.
     *
     * @param \Ollieread\Articles\Models\Article $post
     *
     * @return void
     */
    public function created(Article $post): void
    {
        Redirects::create('/p/' . Ids::encodePosts($post->id), route('articles:article', $post->slug));
    }

    /**
     * Handle the post "update" event.
     *
     * @param \Ollieread\Articles\Models\Article $post
     *
     * @return void
     */
    public function updating(Article $post): void
    {
        if ($post->slug !== $post->getOriginal('slug')) {
            Redirects::create(route('articles:article', $post->getOriginal('slug')), route('articles:article', $post->slug));
        }
    }
}
