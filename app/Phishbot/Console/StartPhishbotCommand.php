<?php

namespace Ollieread\Phishbot\Console;

use Illuminate\Console\Command;
use Ollieread\Phishbot\Commands\Site;
use Ollieread\Phishbot\Support\Phishbot;

class StartPhishbotCommand extends Command
{
    protected $signature = 'phishbot:start';

    protected $description = 'Starts the phishbot';

    /**
     * @var \Ollieread\Phishbot\Support\Phishbot
     */
    private $phishbot;

    private $commands = [
        Site::class,
    ];

    public function __construct(Phishbot $phishbot)
    {
        parent::__construct();
        $this->phishbot = $phishbot;
    }

    public function handle(): void
    {
        array_walk($this->commands, function (string $commandClass) {
            $this->phishbot->mapCommand($this->getLaravel()->make($commandClass));
        });

        $this->phishbot->start();
    }
}
