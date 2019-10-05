<?php

namespace Ollieread\Core\Validators;

use Illuminate\Validation\Rule;
use Ollieread\Core\Models\Redirect;
use Ollieread\Core\Support\Validator;

class CreateRedirectValidator extends Validator
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'from' => ['required', Rule::unique((new Redirect)->getTable(), 'from')],
            'to'   => ['required'],
        ];
    }
}
