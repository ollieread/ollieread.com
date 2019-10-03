<?php

namespace Ollieread\Articles\Support\Markdown\Renderers;

use Illuminate\Support\Str;
use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Block\Element\Heading;
use League\CommonMark\Block\Renderer\BlockRendererInterface;
use League\CommonMark\ElementRendererInterface;
use League\CommonMark\HtmlElement;

class HeadingRenderer implements BlockRendererInterface
{

    /**
     * @param AbstractBlock            $block
     * @param ElementRendererInterface $htmlRenderer
     * @param bool                     $inTightList
     *
     * @return HtmlElement
     */
    public function render(AbstractBlock $block, ElementRendererInterface $htmlRenderer, bool $inTightList = false)
    {
        if (! ($block instanceof Heading)) {
            throw new \InvalidArgumentException('Incompatible block type: ' . \get_class($block));
        }

        // We increase this by 1 to avoid h1 tags
        $tag        = 'h' . ($block->getLevel() + 1);
        $attrs      = $block->getData('attributes', []);
        $contents   = $htmlRenderer->renderInlines($block->children());
        $anchorName = Str::slug(strip_tags($contents));
        $anchor     = new HtmlElement('a', [
            'id'    => $anchorName,
            'href'  => '#' . $anchorName,
            'class' => 'link--anchor',
        ]);

        return new HtmlElement($tag, $attrs, $anchor . $htmlRenderer->renderInlines($block->children()));
    }
}
