<?php

namespace hunomina\Validator\Json\Exception;

use Exception;

class InvalidSchemaException extends Exception
{
    // thrown when a list schema or object schema is missing
    public const MISSING_SCHEMA = 1;

    // thrown when a field type is missing
    public const MISSING_TYPE = 2;

    // thrown when a schema with an invalid/unknown type is being validated
    public const INVALID_SCHEMA_TYPE = 3;

    // thrown when an invalid schema rule is encountered
    public const INVALID_SCHEMA_RULE = 4;

    // thrown when an object schema is invalid
    public const INVALID_CHILD_SCHEMA = 5;
}