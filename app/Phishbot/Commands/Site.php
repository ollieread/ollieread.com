<?php

namespace Ollieread\Phishbot\Commands;

use CharlotteDunois\Yasmin\Models\Message;
use Ollieread\Phishbot\Support\Command;
use Ollieread\Phishbot\Support\Phishbot;

class Site extends Command
{
    protected $listensFor = '!site';

    protected $description = 'Returns a link to the site';

    public function handle(Message $message, Phishbot $phishbot): void
    {
        $phishbot->bot()->reply($message->author . ' https://ollieread.com');
    }
}
