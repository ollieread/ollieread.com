<?php

namespace Ollieread\Articles\Support\Markdown\Parsers;

use League\CommonMark\Block\Parser\BlockParserInterface;
use League\CommonMark\ContextInterface;
use League\CommonMark\Cursor;
use Ollieread\Articles\Support\Markdown\Elements\Notice;

class NoticeParser implements BlockParserInterface
{

    /**
     * @param ContextInterface $context
     * @param Cursor           $cursor
     *
     * @return bool
     */
    public function parse(ContextInterface $context, Cursor $cursor): bool
    {
        if ($cursor->isIndented()) {
            return false;
        }

        $indent = $cursor->getIndent();
        $notice = $cursor->match('/^(\:\:\:[a-z\-]*)/');

        if ($notice === null) {
            return false;
        }

        $notice       = ltrim($notice, " \t");
        $noticeLength = strlen($notice);
        $type         = substr($notice, 3);
        $context->addBlock(new Notice($noticeLength, $indent, $type));

        return true;
    }
}
