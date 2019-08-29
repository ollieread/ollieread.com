<?php

namespace Ollieread\Phishbot\Support;

use CharlotteDunois\Yasmin\Interfaces\TextChannelInterface;
use CharlotteDunois\Yasmin\Models\GuildMember;
use Ollieread\Phishbot\Contracts\Command as Contract;
use Ollieread\Phishbot\Phishbot;

abstract class Command implements Contract
{
    protected $command     = '';
    protected $description = '';
    protected $types       = 0;

    /**
     * @var Phishbot
     */
    protected $phishbot;

    public function command(): string
    {
        return $this->command;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function phishbot(): Phishbot
    {
        return $this->phishbot;
    }

    public function setPhishbot(Phishbot $phishbot)
    {
        $this->phishbot = $phishbot;
    }

    public function type(int $type): bool
    {
        return $this->types === 0 ? true : $this->types & $type;
    }

    public function authorised(GuildMember $user, ?TextChannelInterface $channel = null): bool
    {
        return true;
    }
}
