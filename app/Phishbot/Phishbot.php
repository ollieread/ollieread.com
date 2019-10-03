<?php

namespace Ollieread\Phishbot;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use CharlotteDunois\Yasmin\Interfaces\CategoryChannelInterface;
use CharlotteDunois\Yasmin\Interfaces\DMChannelInterface;
use CharlotteDunois\Yasmin\Interfaces\GroupDMChannelInterface;
use Illuminate\Support\Collection;
use JABirchall\BotMan\Drivers\Discord\DiscordDriver;
use Ollieread\Phishbot\Contracts\Command;
use Ollieread\Phishbot\Support\ChannelTypes;
use React\EventLoop\Factory;

/**
 * Class Phishbot
 *
 * @mixin BotMan
 *
 * @package Ollieread\Phishbot
 */
class Phishbot
{
    /**
     * @var \BotMan\BotMan\BotMan
     */
    private $botman;

    /**
     * @var \React\EventLoop\LoopInterface
     */
    private $loop;

    /**
     * @var \Illuminate\Support\Collection
     */
    private $commands;

    public function __construct()
    {
        DriverManager::loadDriver(DiscordDriver::class);

        $this->loop     = Factory::create();
        $this->botman   = BotManFactory::createForDiscord(['discord' => config('botman.discord')], $this->loop);
        $this->commands = new Collection;
    }

    public function __call($name, $arguments)
    {
        return call_user_func([$this->bot(), $name], ...$arguments);
    }

    public function addCommand(Command $command)
    {
        $command->setPhishbot($this);
        $this->commands->put($command->command(), $command);
        $this->bot()->hears($command->command(), function (BotMan $botMan, ...$arguments) use ($command) {
            $this->handleCommand($botMan, $command, $arguments);
        });
        $this->commands = $this->commands->sortKeys();
    }

    public function bot(): BotMan
    {
        return $this->botman;
    }

    public function getCommands(): Collection
    {
        return $this->commands;
    }

    public function start(): void
    {
        $this->botman->listen();
        $this->loop->run();
    }

    private function handleCommand(BotMan $botMan, Command $command, array $arguments)
    {
        /**
         * @var \CharlotteDunois\Yasmin\Models\Message $message
         */
        $message     = $botMan->getMessage()->getPayload();
        $channel     = $message->channel;
        $channelType = ChannelTypes::GUILD_TEXT;

        if ($message->channel instanceof CategoryChannelInterface) {
            $channelType = ChannelTypes::GUILD_CATEGORY;
        } else if ($message->channel instanceof DMChannelInterface) {
            $channelType = ChannelTypes::DM;
        } else if ($message->channel instanceof GroupDMChannelInterface) {
            $channelType = ChannelTypes::GROUP_DM;
        }

        if (! $command->type($channelType) || ! $command->authorised($message->member, $channel)) {
            return;
        }

        $command->handle($message, $channel, $arguments);
    }
}
