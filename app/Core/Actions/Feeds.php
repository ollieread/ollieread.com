<?php

namespace Ollieread\Core\Actions;

use Ollieread\Core\Services\Feeds as FeedsService;
use Ollieread\Core\Support\Action;

class Feeds extends Action
{
    /**
     * @var \Ollieread\Core\Services\Feeds
     */
    private $feeds;

    public function __construct(FeedsService $feeds)
    {
        $this->feeds = $feeds;
    }

    public function rss()
    {
        return $this->response()->make($this->feeds->getRss(), 200, [
            'Content-Type' => 'application/rss+xml',
        ]);
    }

    public function sitemap()
    {
        return $this->response()->make($this->feeds->getSitemap(), 200, [
            'Content-Type' => 'application/xml',
        ]);
    }
}
