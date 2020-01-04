<?php

namespace hunomina\DataValidator\Rule\Json;

use hunomina\DataValidator\Exception\Json\InvalidDataException;

class BooleanRule extends JsonRule
{
    /**
     * @param $data
     * @return bool
     * @throws InvalidDataException
     */
    public function validate($data): bool
    {
        if (!is_bool($data)) {
            throw new InvalidDataException('Must be a boolean', InvalidDataException::INVALID_DATA_TYPE);
        }
        return true;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::BOOLEAN_TYPE;
    }
}