<?php

namespace Ollieread\Core\Support\Rules;

use Countable;
use Illuminate\Contracts\Validation\Rule;
use Ollieread\Core\Support\Status;
use Symfony\Component\HttpFoundation\File\File;

class ArticleLive implements Rule
{
    /**
     * @var array
     */
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $status = (int) ($this->data['status'] ?? 0);

        if ($status > Status::REVIEWING) {
            if ($value === null) {
                return false;
            }

            if (is_string($value) && trim($value) === '') {
                return false;
            }

            if ((is_array($value) || $value instanceof Countable) && count($value) < 1) {
                return false;
            }

            if ($value instanceof File) {
                return (string) $value->getPath() !== '';
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string|array
     */
    public function message()
    {
        return trans('validation.required_live');
    }
}
