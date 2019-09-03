<?php

namespace Ollieread\Core\Support;

use Illuminate\Container\Container;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

abstract class Validator
{
    /**
     * @param array                                    $data
     * @param \Illuminate\Database\Eloquent\Model|null $entity
     * @param array                                    $extra
     *
     * @return \Ollieread\Core\Support\Validator
     */
    public static function validate(array $data = [], ?Model $entity = null, array $extra = []): Validator
    {
        $validator = new static($data, $entity);
        $validator->setExtra($extra);
        $validator->fire();

        return $validator;
    }

    /**
     * @var null|\Illuminate\Database\Eloquent\Model
     */
    protected $model;
    /**
     * @var array
     */
    protected $data;
    /**
     * @var array
     */
    protected $extra = [];
    /**
     * @var \Illuminate\Contracts\Validation\Validator
     */
    protected $validator;

    /**
     * BaseValidator constructor.
     *
     * @param array                                    $data
     * @param \Illuminate\Database\Eloquent\Model|null $entity
     */
    private function __construct(array $data = [], ?Model $entity = null)
    {
        $this->data  = $data;
        $this->model = $entity;
    }

    /**
     * @return array
     */
    protected function attributes(): array
    {
        return [];
    }

    /**
     * @return array
     */
    protected function data(): array
    {
        return $this->data;
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failed(): void
    {
        throw new ValidationException($this->validator);
    }

    /**
     * @return bool
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function fire(): bool
    {
        $validator = $this->validator();
        $this->preValidation();

        if ($validator->fails()) {
            $this->failed();
        }

        return true;
    }

    /**
     * @return \Illuminate\Contracts\Validation\Validator
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function makeValidator(): ValidatorContract
    {
        $factory = Container::getInstance()->make(Factory::class);

        return $factory->make($this->data(), $this->rules(), $this->messages());
    }

    /**
     * @return array
     */
    protected function messages(): array
    {
        return [];
    }

    protected function preValidation(): void
    {
    }

    /**
     * @return array
     */
    abstract public function rules(): array;

    public function setExtra(array $extra): void
    {
        $this->extra = $extra;
    }

    /**
     * @return \Illuminate\Contracts\Validation\Validator
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function validator(): ValidatorContract
    {
        if (! $this->validator) {
            $this->validator = $this->makeValidator();
        }

        return $this->validator;
    }
}
