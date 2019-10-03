<?php

namespace Ollieread\Articles\Operations;

use Ollieread\Articles\Validators\DeleteTopicValidator;
use Ollieread\Core\Models\Topic;

class DeleteTopic
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
        DeleteTopicValidator::validate($this->input, $this->topic);

        $this->topic->articles()->detach();

        return $this->topic->delete();
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
