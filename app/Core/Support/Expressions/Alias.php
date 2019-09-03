<?php

namespace Ollieread\Core\Support\Expressions;

use Illuminate\Database\Query\Expression;

class Alias extends Expression
{
    /**
     * @var string
     */
    protected $alias;

    /**
     * Alias constructor.
     *
     * @param mixed  $value
     * @param string $alias
     */
    public function __construct($value, string $alias)
    {
        parent::__construct($value);
        $this->alias = $alias;
    }

    public function getValue()
    {
        return parent::getValue() . ' AS ' . $this->alias;
    }
}