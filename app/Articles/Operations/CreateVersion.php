<?php

namespace Ollieread\Articles\Operations;

use Ollieread\Articles\Validators\CreateVersionValidator;
use Ollieread\Core\Models\Version;

class CreateVersion
{
    /**
     * @var array
     */
    private $input;

    public function perform(): bool
    {
        CreateVersionValidator::validate($this->input);

        $version = (new Version)->fill($this->input);

        return $version->save();
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
}
