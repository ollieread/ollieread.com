<?php

namespace Ollieread\Articles\Support;

use Illuminate\Contracts\Support\Arrayable;
use Ollieread\Articles\Models\Article;

class ArticleMetadata implements Arrayable
{
    /**
     * @var \Ollieread\Articles\Models\Article
     */
    private $article;

    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    public function __toString(): string
    {
        return ' <script type="application/ld+json">' . json_encode($this->toArray(), JSON_UNESCAPED_SLASHES) . '</script>';
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        $array = [
            '@context'         => 'https://schema.org',
            '@type'            => 'Article',
            'publisher'        => [
                '@type' => 'Organization',
                'name'  => 'ollieread.com',
                'logo'  => [
                    '@type' => 'ImageObject',
                    'url'   => asset('images/small-me-icon.png', true),
                ],
            ],
            'author'           => [
                '@type'  => 'Person',
                'name'   => 'Ollie Read',
                'url'    => 'https://ollieread.com',
                'sameAs' => [
                    'https://www.linkedin.com/in/ollieread/',
                    'https://twitter.com/ollieread',
                    'https://www.instagram.com/ollieread/',
                    'https://github.com/ollieread',
                    'https://stackoverflow.com/users/3104359/ollieread',
                ],
            ],
            'headline'         => $this->article->heading ?? $this->article->name,
            'url'              => route('articles:article', $this->article->slug, true),
            'datePublished'    => $this->article->post_at->toW3cString(),
            'dateModified'     => $this->article->updated_at->toW3cString(),
            'keywords'         => str_replace(',', '', $this->article->keywords),
            'description'      => $this->article->excerpt,
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id'   => 'https://ollieread.com/',
            ],
            'image' => [
                '@type' => 'ImageObject',
                'url'   => $this->article->image ?? asset('images/small-me-icon.png', true),
            ]
        ];

        if ($this->article->heading) {
            $array['alternativeHeadline'] = $this->article->name;
        }

        return $array;
    }
}
