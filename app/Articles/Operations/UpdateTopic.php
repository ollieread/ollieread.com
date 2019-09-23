<?php

namespace Ollieread\Articles\Operations;

use Ollieread\Articles\Validators\UpdateTopicValidator;
use Ollieread\Core\Models\Topic;

class UpdateTopic
{
    /**
     * @var \Ollieread\Core\Models\Topic
     */
    private $topic;

    /**
     * @var array
     */
    private $input;

    public function perform(): bool
    {
        UpdateTopicValidator::validate($this->input, $this->topic);

        $this->topic->fill($this->input);

        return $this->topic->save();
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
     * @param \Ollieread\Core\Models\Topic $topic
     *
     * @return $this
     */
    public function setTopic(Topic $topic): self
    {
        $this->topic = $topic;

        return $this;
    }
}
