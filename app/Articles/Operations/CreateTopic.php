<?php

namespace Ollieread\Articles\Operations;

use Ollieread\Articles\Validators\CreateTopicValidator;
use Ollieread\Core\Models\Topic;

class CreateTopic
{
    /**
     * @var array
     */
    private $input;

    public function perform(): bool
    {
        CreateTopicValidator::validate($this->input);

        $topic = (new Topic)->fill($this->input);

        return $topic->save();
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
