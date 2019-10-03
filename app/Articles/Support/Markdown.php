<?php

namespace Ollieread\Articles\Support;

use League\CommonMark\Block\Element as Blocks;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment;
use League\CommonMark\Extras\CommonMarkExtrasExtension;
use League\CommonMark\Inline\Element as Inlines;
use Ollieread\Articles\Support\Markdown\Comments;

class Markdown
{
    public static function parseComment(string $comment): string
    {
        $environment = Environment::createCommonMarkEnvironment();
        $environment
            ->addExtension(new CommonMarkExtrasExtension)
            ->addBlockRenderer(Blocks\Heading::class, new Comments\Renderer\HeadingRenderer);

        return (new CommonMarkConverter([
            'html_input' => 'escape',
        ], $environment))->convertToHtml($comment);
    }

    public static function parse(string $comment): string
    {
        $environment = Environment::createCommonMarkEnvironment()
            ->addExtension(new CommonMarkExtrasExtension)
            ->addBlockParser(new Markdown\Parsers\HeadingTOCParser, 100)
            ->addBlockParser(new Markdown\Parsers\TOCParser)
            ->addBlockParser(new Markdown\Parsers\NoticeParser)
            ->addBlockRenderer(Markdown\Elements\TableOfContents::class, new Markdown\Renderers\TOCRenderer)
            ->addBlockRenderer(Blocks\Heading::class, new Markdown\Renderers\HeadingRenderer)
            ->addBlockRenderer(Markdown\Elements\Notice::class, new Markdown\Renderers\NoticeRenderer)
            ->addInlineRenderer(Inlines\Link::class, new Markdown\Renderers\LinkRenderer);

        return (new CommonMarkConverter([], $environment))->convertToHtml($comment);
    }
}
