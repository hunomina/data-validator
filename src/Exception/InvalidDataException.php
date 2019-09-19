<?php

namespace hunomina\Validator\Json\Exception;

use Exception;

class InvalidDataException extends Exception
{
    public const INVALID_DATA_TYPE = 0;

    public const INVALID_JSON_DATA = 1;
}