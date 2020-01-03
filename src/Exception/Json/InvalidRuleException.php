<?php

namespace hunomina\Validator\Json\Exception\Json;

use Exception;

class InvalidRuleException extends Exception
{
    // thrown when a rule is created with an invalid type
    public const INVALID_RULE_TYPE = 1;

    // thrown when an invalid `length` option is encountered
    public const INVALID_LENGTH_RULE = 10;
    // thrown when an invalid `pattern` option is encountered
    public const INVALID_PATTERN_RULE = 11;
    // thrown when an invalid `min` option is encountered
    public const INVALID_MIN_RULE = 12;
    // thrown when an invalid `max` option is encountered
    public const INVALID_MAX_RULE = 13;
    // thrown when an invalid `enum` option is encountered
    public const INVALID_ENUM_RULE = 14;
    // thrown when an invalid `date-format` option is encountered
    public const INVALID_DATE_FORMAT_RULE = 15;
    // thrown when an invalid `empty` option is encountered
    public const INVALID_EMPTY_RULE = 16;
    // thrown when an invalid `null` option is encountered
    public const INVALID_NULL_RULE = 17;
    // thrown when an invalid `optional` option is encountered
    public const INVALID_OPTIONAL_RULE = 25;

    // thrown when an invalid `list-null` rule is encountered
    public const INVALID_LIST_NULL_RULE = 20;
    // thrown when an invalid `list-length` rule is encountered
    public const INVALID_LIST_LENGTH_RULE = 21;
    // thrown when an invalid `list-min` rule is encountered
    public const INVALID_LIST_MIN_RULE = 22;
    // thrown when an invalid `list-max` rule is encountered
    public const INVALID_LIST_MAX_RULE = 23;
    // thrown when an invalid `list-empty` rule is encountered
    public const INVALID_LIST_EMPTY_RULE = 24;
}