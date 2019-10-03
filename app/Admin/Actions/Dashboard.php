<?php

namespace Ollieread\Admin\Actions;

use Ollieread\Core\Support\Action;

class Dashboard extends Action
{
    public function __invoke()
    {
        return $this->response()->view('admin.dashboard');
    }
}
