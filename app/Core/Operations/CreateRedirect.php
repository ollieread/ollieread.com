<?php

namespace Ollieread\Core\Operations;

use Ollieread\Core\Models\Redirect;
use Ollieread\Core\Validators\CreateRedirectValidator;

class CreateRedirect
{
    /**
     * @var array
     */
    private $input;

    public function perform(): ?Redirect
    {
        CreateRedirectValidator::validate($this->input);

        $redirect = (new Redirect)->fill($this->input);

        if ($redirect->save()) {
            return $redirect;
        }

        return null;
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
