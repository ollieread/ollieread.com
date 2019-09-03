<?php

namespace Ollieread\Users\Actions\Admin\Versions;

use Ollieread\Articles\Operations\GetTopics;
use Ollieread\Articles\Operations\GetVersions;
use Ollieread\Core\Support\Action;

class Index extends Action
{
    public function __invoke()
    {
        $versions = (new GetVersions)
            ->setLimit(20)
            ->setPaginate(true)
            ->perform();

        return $this->response()->view('users.admin.versions.index', compact('versions'));
    }
}
