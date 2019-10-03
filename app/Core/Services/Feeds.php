<?php

namespace Ollieread\Core\Services;

use Closure;
use Illuminate\Filesystem\FilesystemManager;
use Ollieread\Articles\Models\Article;
use Ollieread\Articles\Models\Category;
use Ollieread\Articles\Operations\GetArticles;
use Ollieread\Articles\Operations\GetCategories;
use Ollieread\Core\Support\Status;
use XMLWriter;

class Feeds
{
    private const RSS     = 'feeds/feed.rss';

    private const SITEMAP = 'feeds/sitemap.xml';

    /**
     * @var \Illuminate\Contracts\Filesystem\Filesystem
     */
    private $filesystem;

    public function __construct(FilesystemManager $filesystemManager)
    {
        $this->filesystem = $filesystemManager->drive('local');
    }

    public function getRss(): string
    {
        if ($this->filesystem->exists(self::RSS)) {
            return $this->filesystem->get(self::RSS);
        }

        return $this->buildRss();
    }

    public function getSitemap(): string
    {
        if ($this->filesystem->exists(self::SITEMAP)) {
            return $this->filesystem->get(self::SITEMAP);
        }

        return $this->buildSitemap();
    }

    public function invalidateRss(): void
    {
        if ($this->filesystem->exists(self::RSS)) {
            $this->filesystem->delete(self::RSS);
        }
    }

    public function invalidateSitemap(): void
    {
        if ($this->filesystem->exists(self::SITEMAP)) {
            $this->filesystem->delete(self::SITEMAP);
        }
    }

    protected function buildRss()
    {
        $items = $this->buildArticles(static function (Article $article) {
            return [
                'link'        => route('articles:article', $article->slug),
                'guid'        => route('articles:article', $article->slug),
                'pubDate'     => $article->updated_at->format('D, d M Y H:i:s'),
                'title'       => $article->title ?? $article->name,
                'description' => $article->excerpt,
            ];
        });
        $lines = [
            '<rss version=\'2.0\' xmlns:atom=\'http://www.w3.org/2005/Atom\'>',
            '<channel>',
            '<title>Ollie Read RSS Feed</title>',
            '<description>RSS Feed for ollieread.com</description>',
        ];

        foreach ($items as $item) {
            foreach ($item as $key => $value) {
                $lines[] = '<item>';
                $lines[] = sprintf('<%s>%s</%s>', $key, htmlentities($value, ENT_XML1), $key);
                $lines[] = '</item>';
            }
        }

        $lines[] = '</channel>';
        $lines[] = '</rss>';

        $feed = implode("\n", $lines);
        $this->filesystem->put(self::RSS, $feed);

        return $feed;
    }

    protected function buildSitemap(): string
    {
        $items = [
            $this->buildArticles(static function (Article $article) {
                return [
                    'loc'        => route('articles:article', $article->slug),
                    'lastmod'    => $article->updated_at->toW3cString(),
                    'changefreq' => 'monthly',
                    'priority'   => 0.8,
                ];
            }),
            $this->buildCategories(static function (Category $category) {
                return [
                    'loc'        => route('articles:category', $category->slug),
                    'lastmod'    => $category->updated_at->toW3cString(),
                    'changefreq' => 'yearly',
                    'priority'   => 0.6,
                ];
            }),
        ];
        $items = array_merge(...$items);

        $document = new XMLWriter;
        $document->openMemory();
        $document->startDocument('1.0');
        $document->startElement('urlset');
        $document->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        foreach ($items as $item) {
            $document->startElement('url');

            foreach ($item as $tag => $value) {
                $document->startElement($tag);
                $document->text($value);
                $document->endElement();
            }

            $document->endElement();
        }

        $document->endElement();
        $document->endDocument();

        $sitemap = $document->outputMemory();

        $this->filesystem->put(self::SITEMAP, $sitemap);

        return $sitemap;
    }

    private function buildArticles(Closure $transformer): array
    {
        return (new GetArticles)
            ->setActiveOnly(true)
            ->setStatuses(Status::PUBLIC)
            ->perform()
            ->map($transformer)
            ->toArray();
    }

    private function buildCategories(Closure $transformer): array
    {
        return (new GetCategories())
            ->perform()
            ->map($transformer)
            ->toArray();
    }
}
