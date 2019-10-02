<?php

namespace Ollieread\Phishbot\Commands;

use CharlotteDunois\Yasmin\Interfaces\DMChannelInterface;
use CharlotteDunois\Yasmin\Interfaces\TextChannelInterface;
use CharlotteDunois\Yasmin\Models\Message;
use Ollieread\Phishbot\Support\Command;

class HelpCommand extends Command
{
    protected $command     = '!help';

    protected $description = 'Returns this help menu';

    public function handle(Message $message, TextChannelInterface $channel, array $arugments = []): void
    {
        $helpResponse  = [];
        $commandLength = 0;
        $this->phishbot()->getCommands()->each(static function (Command $command) use (&$helpResponse, &$commandLength, $message, $channel) {
            if (! $command->authorised($message->member, $channel)) {
                return;
            }

            $commandHelp = $command->help();

            if (! $commandHelp) {
                $helpResponse[$command->command()] = $command->description();

                if (strlen($command->command()) > $commandLength) {
                    $commandLength = strlen($command->command());
                }
            } else {
                foreach ($commandHelp as $subCommand => $subDescription) {
                    $helpResponse[$subCommand] = $subDescription;

                    if (strlen($subCommand) > $commandLength) {
                        $commandLength = strlen($subCommand);
                    }
                }
            }
        });

        if ($helpResponse) {
            $commandLength += 4;
            $output        = [];

            foreach ($helpResponse as $command => $description) {
                $output[] = str_pad($command, $commandLength, ' ') . $description;
            }

            if ($output) {
                $message->author->createDM()->done(static function (DMChannelInterface $dm) use ($output) {
                    $dm->send('```' . implode("\n", $output) . '```');
                });
            }
        }
    }

    public function help(): array
    {
        return [];
    }
}
