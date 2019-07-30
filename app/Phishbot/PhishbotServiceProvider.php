<?php

namespace Ollieread\Phishbot;

use Illuminate\Support\ServiceProvider;
use Ollieread\Phishbot\Console\StartPhishbotCommand;

class PhishbotServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->commands([
            StartPhishbotCommand::class,
        ]);
    }
}
