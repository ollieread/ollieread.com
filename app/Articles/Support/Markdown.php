<?php

namespace Ollieread\Articles\Support;

use League\CommonMark\Block\Element as Blocks;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment;
use Ollieread\Articles\Support\Markdown\Comments;

class Markdown
{
    public static function parseComment(string $comment): string
    {
        $environment = Environment::createCommonMarkEnvironment();
        $environment->addBlockRenderer(Blocks\Heading::class, new Comments\HeadingRenderer);

        return (new CommonMarkConverter([
            'html_input' => 'escape',
        ], $environment))->convertToHtml($comment);
    }

    public static function parse(string $comment): string
    {
        $environment = Environment::createCommonMarkEnvironment();

        return (new CommonMarkConverter([], $environment))->convertToHtml($comment);
    }
}
