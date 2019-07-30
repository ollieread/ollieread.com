<?php

namespace Ollieread\Phishbot\Support;

use Ollieread\Phishbot\Contracts\BotCommand;

class Command implements BotCommand
{
    /**
     * @var array
     */
    protected $listensFor = [];

    /**
     * @var array
     */
    protected $requires = [];

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @var array
     */
    protected $in = [];

    public function getDescription(): string
    {
        return $this->description;
    }

    public function in(): array
    {
        return $this->in;
    }

    /**
     * @return string|array
     */
    public function listensFor(): array
    {
        return is_array($this->listensFor) ? $this->listensFor : [$this->listensFor];
    }

    public function needPermissions(): array
    {
        return $this->requires;
    }
}
