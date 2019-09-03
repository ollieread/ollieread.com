<?php

namespace Ollieread\Users\Actions\Admin;

use Ollieread\Core\Support\Action;

class Dashboard extends Action
{
    public function __invoke()
    {
        return $this->response()->view('users.admin.dashboard');
    }
}
