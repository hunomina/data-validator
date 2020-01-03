<?php

namespace hunomina\Validator\Json\Rule\Json;

use hunomina\Validator\Json\Exception\Json\InvalidDataException;
use hunomina\Validator\Json\Rule\Json\Traits\EnumCheckTrait;
use hunomina\Validator\Json\Rule\Json\Traits\NullCheckTrait;
use hunomina\Validator\Json\Rule\Json\Traits\PatternCheckTrait;

class CharacterRule extends JsonRule
{
    use NullCheckTrait;
    use PatternCheckTrait;
    use EnumCheckTrait;

    /**
     * @param $data
     * @return bool
     * @throws InvalidDataException
     */
    public function validate($data): bool
    {
        if ($data === null) {
            if (!$this->nullable) {
                throw new InvalidDataException('Can not be null', InvalidDataException::NULL_VALUE_NOT_ALLOWED);
            }
            return true;
        }

        if (!is_string($data) || strlen($data) !== 1) {
            throw new InvalidDataException('Must be a character', InvalidDataException::INVALID_DATA_TYPE);
        }

        if (!$this->validatePattern($data)) {
            throw new InvalidDataException('Must match the pattern ' . $this->pattern, InvalidDataException::PATTERN_NOT_MATCHED);
        }

        if (!$this->validateEnum($data)) {
            throw new InvalidDataException('Must be one of the following values : ' . implode(', ', $this->enum), InvalidDataException::UNAUTHORIZED_VALUE);
        }

        return true;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::CHAR_TYPE;
    }
}