<?php

namespace Ollieread\Articles\Operations;

use Ollieread\Articles\Validators\DeleteVersionValidator;
use Ollieread\Core\Models\Version;

class DeleteVersion
{
    /**
     * @var \Ollieread\Core\Models\Version
     */
    private $version;

    /**
     * @var array
     */
    private $input;

    public function perform(): bool
    {
        DeleteVersionValidator::validate($this->input, $this->version);

        $this->version->articles()->detach();

        return $this->version->delete();
    }

    /**
     * @param array $input
     *
     * @return $this
     */
    public function setInput(array $input): self
    {
        $this->input = $input;

        return $this;
    }

    /**
     * @param \Ollieread\Core\Models\Version $version
     *
     * @return $this
     */
    public function setVersion(Version $version): self
    {
        $this->version = $version;

        return $this;
    }
}
