<?php

namespace Ollieread\Core\Operations;

use Ollieread\Core\Models\Redirect;

class DeleteRedirect
{
    /**
     * @var string
     */
    private $uri;

    /**
     * @param string $uri
     *
     * @return $this
     */
    public function setUri(string $uri): self
    {
        $this->uri = $uri;

        return $this;
    }

    public function perform(): bool
    {
        return Redirect::query()
            ->where('from', '=', $this->uri)
            ->orWhere('to', '=', $this->uri)->delete() > 0;
    }
}
