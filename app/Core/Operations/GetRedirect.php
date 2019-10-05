<?php

namespace Ollieread\Core\Operations;

use Ollieread\Core\Models\Redirect;

class GetRedirect
{
    /**
     * @var string|null
     */
    private $from;

    /**
     * @var string|null
     */
    private $to;

    /**
     * @var int|null
     */
    private $id;

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

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

        if ($this->id) {
            $query->where('id','=', $this->id);
        }

        return $query->first();
    }
}
