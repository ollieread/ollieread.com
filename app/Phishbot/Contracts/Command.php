<?php

namespace Ollieread\Phishbot\Contracts;

use CharlotteDunois\Yasmin\Interfaces\TextChannelInterface;
use CharlotteDunois\Yasmin\Models\GuildMember;
use CharlotteDunois\Yasmin\Models\Message;
use Ollieread\Phishbot\Phishbot;

interface Command
{
    public function authorised(GuildMember $user, ?TextChannelInterface $channel = null): bool;

    public function command(): string;

    public function description(): string;

    public function type(int $type): bool;

    public function setPhishbot(Phishbot $phishbot);

    public function phishbot(): Phishbot;

    public function handle(Message $message, TextChannelInterface $channel, array $arugments = []): void;

    public function help(): array;
}
