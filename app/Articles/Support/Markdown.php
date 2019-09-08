<?php

namespace Ollieread\Articles\Support;

use League\CommonMark\CommonMarkConverter;

class Markdown
{
    public static function parseComment(string $comment): string
    {
        return (new CommonMarkConverter([
            'html_input' => 'escape',
        ]))->convertToHtml($comment);
    }

    public static function parse(string $comment): string
    {
        return (new CommonMarkConverter)->convertToHtml($comment);
    }
}
