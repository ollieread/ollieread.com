<?php

namespace Ollieread\Articles\Support\Markdown\Parsers;

use League\CommonMark\Block\Parser\BlockParserInterface;
use League\CommonMark\ContextInterface;
use League\CommonMark\Cursor;
use Ollieread\Articles\Support\Markdown\Elements\TableOfContents;

class TOCParser implements BlockParserInterface
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

        if (strpos($cursor->getLine(), '[[toc]]') !== 0) {
            return false;
        }

        $cursor->advanceBy(strlen($cursor->getLine()));

        $context->addBlock(new TableOfContents);
        $context->setBlocksParsed(true);

        return true;
    }
}
