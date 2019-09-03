<?php

namespace Ollieread\Phishbot\Commands;

use CharlotteDunois\Yasmin\Interfaces\TextChannelInterface;
use CharlotteDunois\Yasmin\Models\Message;
use Ollieread\Phishbot\Support\Command;

class LinkCommand extends Command
{
    protected $command     = '!link {type}';
    protected $description = 'Returns a corresponding link';
    protected $links       = [
        'site'         => 'https://ollieread.com',
        'multitenancy' => 'https://multitenancy.dev',
        'articles'     => 'https://ollieread.com/articles',
    ];

    public function handle(Message $message, TextChannelInterface $channel, array $arugments = []): void
    {
        $link = $this->links[$arugments[0]] ?? null;

        if ($link) {
            $this->phishbot()->reply($message->author . ' ' . $link);
        }
    }

    public function help(): array
    {
        return [
            '!link site'         => 'Returns a link to the main site',
            '!link multitenancy' => 'Returns a link to the multitenancy site',
            '!link articles'     => 'Returns a link to all articles',
        ];
    }

    public function type(int $type): bool
    {
        return true;
    }
}
