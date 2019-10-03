<?php

namespace Ollieread\Phishbot\Console;

use Illuminate\Console\Command;
use Ollieread\Phishbot\Commands\HelpCommand;
use Ollieread\Phishbot\Commands\LinkCommand;
use Ollieread\Phishbot\Phishbot;

class StartPhishbotCommand extends Command
{
    protected $signature   = 'phishbot:start';

    protected $description = 'Starts the phishbot';

    /**
     * @var \Ollieread\Phishbot\Phishbot
     */
    private $phishbot;

    private $commands = [
        LinkCommand::class,
        HelpCommand::class,
    ];

    public function __construct(Phishbot $phishbot)
    {
        parent::__construct();
        $this->phishbot = $phishbot;
    }

    public function handle(): void
    {
        array_walk($this->commands, function (string $commandClass) {
            $this->phishbot->addCommand($this->getLaravel()->make($commandClass));
        });

        $this->phishbot->start();
    }
}
