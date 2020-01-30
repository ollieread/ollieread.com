<?php

namespace Ollieread\Articles\Support\Markdown\Elements;

use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Block\Element\AbstractStringContainerBlock;
use League\CommonMark\Block\Element\InlineContainerInterface;
use League\CommonMark\ContextInterface;
use League\CommonMark\Cursor;
use League\CommonMark\Util\RegexHelper;

class Notice extends AbstractStringContainerBlock implements InlineContainerInterface
{

    /**
     * @var int
     */
    private $length;

    /**
     * @var int
     */
    private $offset;

    /**
     * @var string
     */
    private $type;

    /**
     * @param int    $length
     * @param int    $offset
     * @param string $type
     */
    public function __construct(int $length, int $offset, string $type)
    {
        parent::__construct();

        $this->length = $length;
        $this->offset = $offset;
        $this->type   = $type;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return int
     */
    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * @param int $length
     *
     * @return $this
     */
    public function setLength(int $length): self
    {
        $this->length = $length;

        return $this;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     *
     * @return $this
     */
    public function setOffset(int $offset): self
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * Returns true if this block can contain the given block as a child node
     *
     * @param AbstractBlock $block
     *
     * @return bool
     */
    public function canContain(AbstractBlock $block): bool
    {
        return true;
    }

    /**
     * Whether this is a code block
     *
     * Code blocks are extra-greedy - they'll try to consume all subsequent
     * lines of content without calling matchesNextLine() each time.
     *
     * @return bool
     */
    public function isCode(): bool
    {
        return false;
    }

    /**
     * @param Cursor $cursor
     *
     * @return bool
     */
    public function matchesNextLine(Cursor $cursor): bool
    {
        if ($this->length === -1) {
            if ($cursor->isBlank()) {
                $this->lastLineBlank = true;
            }

            return false;
        }

        $match = $cursor->match('/^ {0,' . $this->offset . '}:::/');

        return $match === null;
    }

    public function finalize(ContextInterface $context, int $endLineNumber)
    {
        parent::finalize($context, $endLineNumber);

        if ($this->strings->count() === 1) {
            $this->finalStringContents = '';
        } else {
            $this->finalStringContents = implode("\n", $this->strings->slice(1)) . "\n";
        }
    }

    /**
     * @param ContextInterface $context
     * @param Cursor           $cursor
     */
    public function handleRemainingContents(ContextInterface $context, Cursor $cursor)
    {
        /**
         * @var \Ollieread\Articles\Support\Markdown\Elements\Notice $container
         */
        $container = $context->getContainer();

        $match = RegexHelper::matchAll('/^:::/', $cursor->getLine(), $cursor->getNextNonSpacePosition());

        if ($match !== null && strlen($match[0]) >= $container->getLength()) {
            $this->setLength(-1);

            return;
        }

        $container->addLine($cursor->getRemainder());
    }

    /**
     * @param Cursor $cursor
     * @param int    $currentLineNumber
     *
     * @return bool
     */
    public function shouldLastLineBeBlank(Cursor $cursor, int $currentLineNumber): bool
    {
        return false;
    }
}
