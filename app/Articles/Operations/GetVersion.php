<?php

namespace Ollieread\Articles\Operations;

use Ollieread\Core\Models\Version;

class GetVersion
{
    /**
     * @var string
     */
    private $slug;

    /**
     * @var int
     */
    private $id;

    /**
     * @param string $slug
     *
     * @return $this
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

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

    public function perform(): ?Version
    {
        $query = Version::query();

        if ($this->slug) {
            $query->where('slug', '=', $this->slug);
        }

        if ($this->id) {
            $query->where('id', '=', $this->id);
        }

        return $query->first();
    }
}
