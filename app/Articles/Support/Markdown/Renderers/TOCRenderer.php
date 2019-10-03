<?php

namespace Ollieread\Articles\Support\Markdown\Renderers;

use Illuminate\Support\Str;
use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Block\Renderer\BlockRendererInterface;
use League\CommonMark\ElementRendererInterface;
use League\CommonMark\HtmlElement;
use Ollieread\Articles\Support\Markdown\Elements\TableOfContents;

class TOCRenderer implements BlockRendererInterface
{
    /**
     * @param AbstractBlock            $block
     * @param ElementRendererInterface $htmlRenderer
     * @param bool                     $inTightList
     *
     * @return HtmlElement|null
     */
    public function render(AbstractBlock $block, ElementRendererInterface $htmlRenderer, bool $inTightList = false): ?HtmlElement
    {
        if (! ($block instanceof TableOfContents)) {
            throw new \InvalidArgumentException('Incompatible block type: ' . \get_class($block));
        }

        $headers = $block::getHeaders();
        debug_backtrace();

        if ($headers) {
            $contents = [];

            foreach ($headers as $heading) {
                $contents[] = new HtmlElement('a', [
                    'class' => 'toc__item toc__item--' . $heading[0],
                    'href'  => '#' . Str::slug(strip_tags($heading[1])),
                ], $heading[1]);
            }

            return new HtmlElement('div', ['class' => 'toc toc--expanded'], [
                new HtmlElement('div', ['class' => 'toc__header'], 'Table of Contents'),
                new HtmlElement('div', ['class' => 'toc__body'], $contents),
            ]);
        }

        return null;
    }
}
