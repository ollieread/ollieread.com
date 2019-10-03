<?php

namespace Ollieread\Articles\Support\Markdown\Renderers;

use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Block\Renderer\BlockRendererInterface;
use League\CommonMark\ElementRendererInterface;
use League\CommonMark\HtmlElement;
use Ollieread\Articles\Support\Markdown\Elements\Notice;

class NoticeRenderer implements BlockRendererInterface
{
    /**
     * @param \League\CommonMark\Block\Element\AbstractBlock|Notice $block
     * @param ElementRendererInterface                              $htmlRenderer
     * @param bool                                                  $inTightList
     *
     * @return HtmlElement
     */
    public function render(AbstractBlock $block, ElementRendererInterface $htmlRenderer, bool $inTightList = false)
    {
        if (! ($block instanceof Notice)) {
            throw new \InvalidArgumentException('Incompatible block type: ' . get_class($block));
        }

        return new HtmlElement('div', array_merge($block->getData('attributes', []), [
            'class' => 'notice notice--' . $block->getType(),
        ]), $htmlRenderer->renderInlines($block->children()));
    }
}
