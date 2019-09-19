<?php

namespace hunomina\Validator\Json\Exception;

use Exception;

class InvalidSchemaException extends Exception
{
    public const MISSING_SCHEMA = 0;
    public const MISSING_TYPE = 1;

    public const INVALID_OBJECT_SCHEMA = 10;
    public const INVALID_LENGTH_RULE = 11;
    public const INVALID_PATTERN_RULE = 12;
    public const INVALID_MIN_RULE = 13;
    public const INVALID_MAX_RULE = 14;
    public const INVALID_ENUM_RULE = 15;
    public const INVALID_DATE_FORMAT_RULE = 16;
}