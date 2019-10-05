<?php

namespace Ollieread\Core\Operations;

use Ollieread\Core\Models\Redirect;

class CreateRedirect
{
    /**
     * @var string
     */
    private $from;

    /**
     * @var string
     */
    private $to;

    public function perform(): ?Redirect
    {
        $redirect = (new Redirect)->fill([
            'from' => $this->from,
            'to'   => $this->to,
        ]);

        if ($redirect->save()) {
            return $redirect;
        }

        return null;
    }

    /**
     * @param string $from
     *
     * @return $this
     */
    public function setFrom(string $from): self
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @param string $to
     *
     * @return $this
     */
    public function setTo(string $to): self
    {
        $this->to = $to;

        return $this;
    }
}
