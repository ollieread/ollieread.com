<?php

namespace Ollieread\Phishbot\Support;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use CharlotteDunois\Yasmin\Models\GuildMember;
use CharlotteDunois\Yasmin\Models\TextChannel;
use JABirchall\BotMan\Drivers\Discord\DiscordDriver;
use Ollieread\Phishbot\Contracts\BotCommand;
use React\EventLoop\Factory;

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
     * @var array
     */
    private $commands;

    public function __construct()
    {
        DriverManager::loadDriver(DiscordDriver::class);
        $this->loop   = Factory::create();
        $this->botman = BotManFactory::createForDiscord(['discord' => config('botman.discord')], $this->loop);
    }

    public function bot(): BotMan
    {
        return $this->botman;
    }

    private function checkChannel(TextChannel $channel, array $channels): bool
    {
        return in_array($channel->id, $channels, true);
    }

    private function checkPermissions(GuildMember $member, array $permissions): bool
    {
        /**
         * @var \CharlotteDunois\Yasmin\Models\Role $role
         */
        foreach ($member->roles as $role) {
            if ($role->permissions->has($permissions)) {
                return true;
            }
        }

        return false;
    }

    public function mapCommand(BotCommand $command)
    {
        if (! method_exists($command, 'handle')) {
            throw new \RuntimeException('Command does not have a handle method');
        }

        $this->commands[] = $command;

        $listensFor     = $command->listensFor();
        $commandClosure = static function (BotMan $bot, ...$args) use ($command) {
            $this->performCommand($bot, $command, ...$args);
        };

        foreach ($listensFor as $listen) {
            $this->botman->hears($listen, $commandClosure);
        }
    }

    protected function performCommand(BotMan $bot, BotCommand $command, ...$args): void
    {
        $requiresPermissions = $command->needPermissions();
        $requiresChannels    = $command->in();

        /**
         * @var \CharlotteDunois\Yasmin\Models\Message $message
         */
        $message = $bot->getMessage()->getPayload();

        if ($requiresPermissions && ! $this->checkPermissions($message->guild->members->get($message->author->id), $requiresPermissions)) {
            return;
        }

        if ($requiresChannels && ! $this->checkChannel($message->channel, $requiresChannels)) {
            return;
        }

        $command->handle($bot->getMessage()->getPayload(), $this, ...$args);
    }

    public function start(): void
    {
        $this->botman->listen();
        $this->loop->run();
    }
}
