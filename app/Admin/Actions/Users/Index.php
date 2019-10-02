<?php

namespace Ollieread\Admin\Actions\Users;

use Ollieread\Core\Support\Action;
use Ollieread\Users\Operations\GetUsers;

class Index extends Action
{
    public function __invoke()
    {
        $users = (new GetUsers)
            ->setLimit(20)
            ->perform();

        return $this->response()->view('admin.users.index', compact('users'));
    }
}
