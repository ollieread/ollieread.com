<?php

namespace Ollieread\Articles\Observers;

use Ollieread\Articles\Models\Article;
use Ollieread\Core\Services\Feeds;
use Ollieread\Core\Services\Ids;
use Ollieread\Core\Services\Redirects;

class ArticleObserver
{
    /**
     * @var \Ollieread\Core\Services\Feeds
     */
    private $feeds;

    public function __construct(Feeds $feeds)
    {
        $this->feeds = $feeds;
    }

    /**
     * Handle the article "created" event.
     *
     * @param \Ollieread\Articles\Models\Article $article
     *
     * @return void
     */
    public function created(Article $article): void
    {
        Redirects::create('/p/' . $article->encoded_id, route('articles:article', $article->slug, false));

        $this->feeds->invalidateRss();
        $this->feeds->invalidateSitemap();
    }

    /**
     * Handle the article "update" event.
     *
     * @param \Ollieread\Articles\Models\Article $article
     *
     * @return void
     */
    public function updating(Article $article): void
    {
        if ($article->slug !== $article->getOriginal('slug')) {
            Redirects::create(route('articles:article', $article->getOriginal('slug')), route('articles:article', $article->slug));
        }
    }

    public function updated()
    {
        $this->feeds->invalidateRss();
        $this->feeds->invalidateSitemap();
    }

    public function deleted()
    {
        $this->feeds->invalidateRss();
        $this->feeds->invalidateSitemap();
    }
}
