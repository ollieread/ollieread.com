<?php

namespace Ollieread\Articles\Support\Markdown\Parsers;

use League\CommonMark\Block\Element\Heading;
use League\CommonMark\Block\Parser\BlockParserInterface;
use League\CommonMark\ContextInterface;
use League\CommonMark\Cursor;
use League\CommonMark\Util\RegexHelper;
use Ollieread\Articles\Support\Markdown\Elements\TableOfContents;

class HeadingTOCParser implements BlockParserInterface
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

        $match = RegexHelper::matchAll('/^#{1,6}(?:[ \t]+|$)/', $cursor->getLine(), $cursor->getNextNonSpacePosition());

        if (! $match) {
            return false;
        }

        $cursor->advanceToNextNonSpaceOrTab();
        $cursor->advanceBy(\strlen($match[0]));

        $level = \strlen(\trim($match[0]));
        $str   = $cursor->getRemainder();
        $str   = \preg_replace('/^[ \t]*#+[ \t]*$/', '', $str);
        $str   = \preg_replace('/[ \t]+#+[ \t]*$/', '', $str);

        TableOfContents::addHeader($level, $str);

        $context->addBlock(new Heading($level, $str));
        $context->setBlocksParsed(true);

        return true;
    }
}
