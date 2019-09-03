<?php

use Ollieread\Users\Support\Permissions;

return [
    [
        'name'        => 'User',
        'ident'       => 'user',
        'description' => 'Normal site users',
        'active'      => true,
        'level'       => 0,
        'permissions' => Permissions::CREATE_COMMENT,
    ],
    [
        'name'        => 'Admin',
        'ident'       => 'admin',
        'description' => 'Site admins',
        'active'      => true,
        'level'       => 9999,
        'permissions' => Permissions::ADMIN_MASTER,
    ],
];
