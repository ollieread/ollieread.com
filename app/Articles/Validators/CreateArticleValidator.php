<?php

namespace Ollieread\Articles\Validators;

use Illuminate\Validation\Rule;
use Ollieread\Articles\Models\Article;
use Ollieread\Articles\Models\Category;
use Ollieread\Core\Support\Rules;
use Ollieread\Core\Support\Status;
use Ollieread\Core\Support\Validator;

class CreateArticleValidator extends Validator
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name'            => ['required'],
            'title'           => [],
            'heading'         => [],
            'seo_description' => [],
            'slug'            => ['required', Rule::unique((new Article)->getTable(), 'slug')],
            'excerpt'         => [Rules::live($this->data()), 'max:500'],
            'content'         => ['required'],
            'active'          => ['bool'],
            'status'          => [Rule::in([Status::DRAFT, Status::REVIEWING, Status::PUBLIC, Status::PRIVATE])],
            'post_at'         => [Rules::live($this->data()), 'sometimes:date', 'sometimes:after:now'],
            'category'        => ['required', Rule::exists((new Category)->getTable(), 'id')],
            'parent'          => [],
            'series'          => [],
            'tags'            => [],
            'topics'          => [],
            'versions'        => [],
        ];
    }
}
