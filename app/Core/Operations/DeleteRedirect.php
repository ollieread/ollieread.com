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
     * @var \Ollieread\Core\Models\Redirect|null
     */
    private $redirect;

    /**
     * @param \Ollieread\Core\Models\Redirect $redirect
     *
     * @return $this
     */
    public function setRedirect(Redirect $redirect): self
    {
        $this->redirect = $redirect;

        return $this;
    }

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
        if ($this->redirect) {
            return $this->redirect->delete() > 0;
        }

        return Redirect::query()->where('from', '=', $this->uri)
                ->orWhere('to', '=', $this->uri)
                ->delete() > 0;
    }
}
