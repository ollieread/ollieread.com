<?php

namespace Ollieread\Articles\Support\Markdown\Renderers;

use League\CommonMark\ElementRendererInterface;
use League\CommonMark\HtmlElement;
use League\CommonMark\Inline\Element\AbstractInline;
use League\CommonMark\Inline\Element\Link;
use League\CommonMark\Inline\Renderer\InlineRendererInterface;
use League\CommonMark\Util\ConfigurationAwareInterface;
use League\CommonMark\Util\ConfigurationInterface;
use League\CommonMark\Util\RegexHelper;

class LinkRenderer implements InlineRendererInterface, ConfigurationAwareInterface
{
    /**
     * @var ConfigurationInterface
     */
    protected $config;

    /**
     * @param \League\CommonMark\Inline\Element\AbstractInline|Link $inline
     * @param ElementRendererInterface                              $htmlRenderer
     *
     * @return HtmlElement
     */
    public function render(AbstractInline $inline, ElementRendererInterface $htmlRenderer)
    {
        if (! ($inline instanceof Link)) {
            throw new \InvalidArgumentException('Incompatible inline type: ' . \get_class($inline));
        }

        $attrs             = $inline->getData('attributes', []);
        $attrs['class']    = 'link';
        $forbidUnsafeLinks = ! $this->config->get('allow_unsafe_links');
        $parsedUrl         = parse_url($inline->getUrl());
        $externalLink      = ! empty($parsedUrl['host']) && $parsedUrl['host'] !== config('app.domain');

        if (! ($forbidUnsafeLinks && RegexHelper::isLinkPotentiallyUnsafe($inline->getUrl()))) {
            $attrs['href'] = $externalLink ? route('site:out', ['url' => $inline->getUrl()]) : $inline->getUrl();
        }

        if ($externalLink) {
            $attrs['target'] = '_blank';
            $attrs['class']  .= ' link--external';
        }

        if (isset($inline->data['title'])) {
            $attrs['title'] = $inline->data['title'];
        }

        if (isset($attrs['target']) && $attrs['target'] === '_blank' && ! isset($attrs['rel'])) {
            $attrs['rel'] = 'noopener noreferrer';
        }

        return new HtmlElement('a', $attrs, $htmlRenderer->renderInlines($inline->children()));
    }

    /**
     * @param ConfigurationInterface $configuration
     */
    public function setConfiguration(ConfigurationInterface $configuration)
    {
        $this->config = $configuration;
    }
}
