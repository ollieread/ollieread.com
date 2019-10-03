<?php

namespace Ollieread\Phishbot\Concerns;

use Illuminate\Support\Collection;

trait MapsCommands
{
    abstract public function getCommands(): Collection;


}
