<?php

namespace Ollieread\Core\Operations;

use Ollieread\Core\Models\Redirect;

class GetRedirect
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

    public function perform()
    {
        return Redirect::query()
            ->where('from', '=', $this->uri)
            ->first();
    }
}
