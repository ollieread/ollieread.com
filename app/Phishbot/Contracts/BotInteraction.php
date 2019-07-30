<?php

namespace Ollieread\Phishbot\Contracts;

interface BotInteraction
{
    public function in(): array;

    /**
     * @return array
     */
    public function listensFor(): array;

    public function needPermissions(): array;
}
