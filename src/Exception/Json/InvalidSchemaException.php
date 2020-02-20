<?php

namespace hunomina\DataValidator\Exception\Json;

/**
 * Class InvalidJsonSchemaException
 * @package hunomina\DataValidator\Exception\Json
 * Thrown when an invalid JsonSchema is set
 */
class InvalidSchemaException extends \hunomina\DataValidator\Exception\InvalidSchemaException
{
    // thrown when a rule type is missing
    public const MISSING_RULE_TYPE = 1;
    // thrown when a schema with an invalid/unknown type is being validated
    public const INVALID_SCHEMA_TYPE = 2;
    // thrown when a schema nullable field is not a boolean
    public const INVALID_SCHEMA_NULLABLE_FIELD = 3;
    // thrown when a schema optional field is not a boolean
    public const INVALID_SCHEMA_OPTIONAL_FIELD = 4;
    // thrown when an invalid schema rule is encountered
    public const INVALID_SCHEMA_RULE = 5;

    // thrown when a list schema or object schema is missing
    public const MISSING_CHILD_SCHEMA = 10;
    // thrown when an object schema is invalid
    public const INVALID_CHILD_SCHEMA = 11;
}