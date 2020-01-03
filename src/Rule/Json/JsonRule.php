<?php

namespace hunomina\Validator\Json\Rule\Json;

use hunomina\Validator\Json\Rule\Rule;

abstract class JsonRule extends Rule
{
    /* Scalar types */
    public const STRING_TYPE = 'string';
    public const CHAR_TYPE = 'character';
    public const INTEGER_TYPE = 'integer';
    public const FLOAT_TYPE = 'float';
    public const NUMERIC_TYPE = 'numeric';
    public const BOOLEAN_TYPE = 'boolean';

    /* List Types */
    public const STRING_LIST_TYPE = self::STRING_TYPE . self::LIST_TYPE_SUFFIX;
    public const CHAR_LIST_TYPE = self::CHAR_TYPE . self::LIST_TYPE_SUFFIX;
    public const INTEGER_LIST_TYPE = self::INTEGER_TYPE . self::LIST_TYPE_SUFFIX;
    public const FLOAT_LIST_TYPE = self::FLOAT_TYPE . self::LIST_TYPE_SUFFIX;
    public const NUMERIC_LIST_TYPE = self::NUMERIC_TYPE . self::LIST_TYPE_SUFFIX;
    public const BOOLEAN_LIST_TYPE = self::BOOLEAN_TYPE . self::LIST_TYPE_SUFFIX;

    public const LIST_TYPE = 'list';
    public const OBJECT_TYPE = 'object';

    protected const LIST_TYPE_SUFFIX = '-list';

    /**
     * @return string
     * Return the rule type as a string
     */
    abstract public function getType(): string;
}