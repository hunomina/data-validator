<?php

namespace hunomina\Validator\Json\Exception;

use Exception;

class InvalidDataException extends Exception
{
    // thrown when the data being validated hs an invalid type
    public const INVALID_DATA_TYPE = 1;

    // thrown when the json being formatted is invalid
    public const INVALID_JSON_DATA = 2;

    // thrown when an invalid/unknown type is being validated
    public const UNKNOWN_DATA_TYPE = 3;

    // thrown when a mandatory field is missing
    public const MANDATORY_FIELD = 4;

    // thrown when a value does not match a given pattern
    public const PATTERN_NOT_MATCHED = 10;

    // thrown when a value is empty but should not
    public const EMPTY_VALUE_NOT_ALLOWED = 11;

    // thrown when a value is null but should not
    public const NULL_VALUE_NOT_ALLOWED = 12;

    // thrown when a value has an invalid length
    public const INVALID_LENGTH = 13;

    // thrown when a list has not enough element or when a string has not enough character
    public const INVALID_MIN_VALUE = 14;

    // thrown when a list has too many elements or when a string has too many characters
    public const INVALID_MAX_VALUE = 15;

    // thrown when a value or the value of a list element is not part of the `enum` property
    public const UNAUTHORIZED_VALUE = 16;

    // thrown when a value has an invalid date format
    public const INVALID_DATE_FORMAT = 17;

    // thrown when a list element is invalid
    public const INVALID_LIST_ELEMENT = 18;

    // thrown when a typed list element is invalid
    public const INVALID_TYPED_LIST_ELEMENT = 19;

    // thrown when a child object (checked by a child schema) is invalid
    public const INVALID_CHILD_OBJECT = 20;
}