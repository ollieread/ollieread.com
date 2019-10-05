<?php

namespace Ollieread\Core\Operations;

use Ollieread\Core\Models\Redirect;
use Ollieread\Core\Validators\UpdateRedirectValidator;

class UpdateRedirect
{
    /**
     * @var \Ollieread\Core\Models\Redirect
     */
    private $redirect;

    /**
     * @var array
     */
    private $input;

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
     * @param array $input
     *
     * @return $this
     */
    public function setInput(array $input): self
    {
        $this->input = $input;

        return $this;
    }

    public function perform(): bool
    {
        UpdateRedirectValidator::validate($this->input, $this->redirect);

        $this->redirect->fill($this->input);

        return $this->redirect->save();
    }
}
