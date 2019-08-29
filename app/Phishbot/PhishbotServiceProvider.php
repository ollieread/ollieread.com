<?php

namespace Ollieread\Phishbot;

use BotMan\BotMan\BotMan;
use Illuminate\Support\ServiceProvider;
use Ollieread\Phishbot\Console\StartPhishbotCommand;

class PhishbotServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(BotMan::class, function () {
            return $this->app->make('botman');
        });

        $this->commands([
            StartPhishbotCommand::class,
        ]);
    }
}
