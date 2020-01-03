<?php

namespace hunomina\Validator\Json\Exception;

use Exception;

class InvalidDataTypeException extends Exception
{
    // thrown when a schema tries to validate an invalid data type
    public const INVALID_DATA_TYPE_USED = 0;
}