<?php

namespace Ollieread\Core\Operations;

use Ollieread\Core\Models\Redirect;

class GetRedirect
{
    /**
     * @var string
     */
    private $from;

    /**
     * @var string
     */
    private $to;

    /**
     * @param string|null $from
     *
     * @return $this
     */
    public function setFrom(?string $from): self
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @param string|null $to
     *
     * @return $this
     */
    public function setTo(?string $to): self
    {
        $this->to = $to;

        return $this;
    }

    public function perform(): ?Redirect
    {
        $query = Redirect::query();

        if ($this->to) {
            $query->where('to', '=', $this->to);
        }

        if ($this->from) {
            $query->where('from', '=', $this->from);
        }

        return $query->first();
    }
}
