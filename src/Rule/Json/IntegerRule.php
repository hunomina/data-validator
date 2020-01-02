<?php

namespace hunomina\Validator\Json\Rule\Json;

use hunomina\Validator\Json\Exception\InvalidDataException;
use hunomina\Validator\Json\Rule\Json\Traits\EnumCheckTrait;
use hunomina\Validator\Json\Rule\Json\Traits\MaximumCheckTrait;
use hunomina\Validator\Json\Rule\Json\Traits\MinimumCheckTrait;
use hunomina\Validator\Json\Rule\Json\Traits\NullCheckTrait;

class IntegerRule extends JsonRule
{
    use NullCheckTrait;
    use MinimumCheckTrait;
    use MaximumCheckTrait;
    use EnumCheckTrait;

    /**
     * @param $data
     * @return bool
     * @throws InvalidDataException
     */
    public function validate($data): bool
    {
        if (!$this->validateNullness($data)) {
            throw new InvalidDataException('Can not be null', InvalidDataException::NULL_VALUE_NOT_ALLOWED);
        }

        if (!is_int($data)) {
            throw new InvalidDataException('Must be an integer', InvalidDataException::INVALID_DATA_TYPE);
        }

        if (!$this->validateMinimum($data)) {
            throw new InvalidDataException('Must be higher than ' . $this->minimum, InvalidDataException::INVALID_MIN_VALUE);
        }

        if (!$this->validateMaximum($data)) {
            throw new InvalidDataException('Must be lower than ' . $this->maximum, InvalidDataException::INVALID_MAX_VALUE);
        }

        if (!$this->validateEnum($data)) {
            throw new InvalidDataException('Must be one of the following values : ' . implode(', ', $this->enum), InvalidDataException::UNAUTHORIZED_VALUE);
        }

        return true;
    }

    /**
     * @param int $data
     * @return bool
     */
    public function validateMaximum(int $data): bool
    {
        if ($this->maximum === null) {
            return true;
        }

        return $data <= $this->maximum;
    }

    /**
     * @param int $data
     * @return bool
     */
    public function validateMinimum(int $data): bool
    {
        if ($this->minimum === null) {
            return true;
        }

        return $data >= $this->minimum;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::INTEGER_TYPE;
    }
}