<?php

namespace Ollieread\Articles\Support\Markdown\Comments;

use InvalidArgumentException;
use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Block\Element\Heading;
use League\CommonMark\Block\Renderer\BlockRendererInterface;
use League\CommonMark\ElementRendererInterface;
use League\CommonMark\HtmlElement;
use function get_class;

class HeadingRenderer implements BlockRendererInterface
{
    /**
     * @param \League\CommonMark\Block\Element\AbstractBlock $block
     * @param ElementRendererInterface                       $htmlRenderer
     * @param bool                                           $inTightList
     *
     * @return HtmlElement
     */
    public function render(AbstractBlock $block, ElementRendererInterface $htmlRenderer, bool $inTightList = false)
    {
        if (! ($block instanceof Heading)) {
            throw new InvalidArgumentException('Incompatible block type: ' . get_class($block));
        }

        $tag = 'span';

        $attrs = $block->getData('attributes', [
            'class' => 'comment__styling-header comment__styling-header--' . $block->getLevel(),
        ]);

        return new HtmlElement($tag, $attrs, $htmlRenderer->renderInlines($block->children()));
    }
}
