<?php

return [
    'token'   => env('DISCORD_TOKEN'),
    'options' => [
        'disableClones'        => true,
        'disableEveryone'      => true,
        'fetchAllMembers'      => false,
        'messageCache'         => true,
        'messageCacheLifetime' => 600,
        'messageSweepInterval' => 600,
        'presenceCache'        => false,
        'userSweepInterval'    => 600,
        'ws.disabledEvents'    => [],
    ],
];
