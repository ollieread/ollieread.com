<?php

namespace Ollieread\Articles\Operations;

use Ollieread\Articles\Validators\UpdateVersionValidator;
use Ollieread\Core\Models\Version;

class UpdateVersion
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
        UpdateVersionValidator::validate($this->input, $this->version);

        $this->version->fill($this->input);

        return $this->version->save();
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
