<?php

namespace hunomina\Validator\Json\Exception;

use Exception;

class InvalidSchemaException extends Exception
{
    // thrown when a list schema or object schema is missing
    public const MISSING_SCHEMA = 0;

    // thrown when a field type is missing
    public const MISSING_TYPE = 1;

    // thrown when a schema with an invalid/unknown type is being validated
    public const INVALID_SCHEMA_TYPE = 2;

    // thrown when an object schema is invalid
    public const INVALID_OBJECT_SCHEMA = 10;

    // thrown when an invalid field type uses the `length` rule
    public const INVALID_LENGTH_RULE = 11;

    // thrown when an invalid field type uses the `pattern` rule
    public const INVALID_PATTERN_RULE = 12;

    // thrown when an invalid field type uses the `min` rule
    public const INVALID_MIN_RULE = 13;

    // thrown when an invalid field type uses the `max` rule
    public const INVALID_MAX_RULE = 14;

    // thrown when an invalid field type uses the `enum` rule
    public const INVALID_ENUM_RULE = 15;

    // thrown when an invalid field type uses the `date-format` rule
    public const INVALID_DATE_FORMAT_RULE = 16;

    // thrown when an invalid field type uses the `empty` rule
    public const INVALID_EMPTY_RULE = 17;
}