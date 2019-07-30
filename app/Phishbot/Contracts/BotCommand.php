<?php

namespace Ollieread\Phishbot\Contracts;

interface BotCommand extends BotInteraction
{
    public function getDescription(): string;
}
