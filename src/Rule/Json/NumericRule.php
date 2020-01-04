<?php

namespace hunomina\DataValidator\Rule\Json;

use hunomina\DataValidator\Exception\Json\InvalidDataException;
use hunomina\DataValidator\Rule\Json\Traits\EnumCheckTrait;
use hunomina\DataValidator\Rule\Json\Traits\MaximumCheckTrait;
use hunomina\DataValidator\Rule\Json\Traits\MinimumCheckTrait;
use hunomina\DataValidator\Rule\Json\Traits\NullCheckTrait;

class NumericRule extends JsonRule
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
        if ($data === null) {
            if (!$this->nullable) {
                throw new InvalidDataException('Can not be null', InvalidDataException::NULL_VALUE_NOT_ALLOWED);
            }
            return true;
        }

        if (!is_int($data) && !is_float($data)) {
            throw new InvalidDataException('Must be a non string number', InvalidDataException::INVALID_DATA_TYPE);
        }

        if (!$this->validateMinimum((float)$data)) {
            throw new InvalidDataException('Must be higher than ' . $this->minimum, InvalidDataException::INVALID_MIN_VALUE);
        }

        if (!$this->validateMaximum((float)$data)) {
            throw new InvalidDataException('Must be lower than ' . $this->maximum, InvalidDataException::INVALID_MAX_VALUE);
        }

        if (!$this->validateEnum($data)) {
            throw new InvalidDataException('Must be one of the following values : ' . implode(', ', $this->enum), InvalidDataException::UNAUTHORIZED_VALUE);
        }

        return true;
    }

    /**
     * @param float $data
     * @return bool
     */
    public function validateMaximum(float $data): bool
    {
        if ($this->maximum === null) {
            return true;
        }

        return $data <= $this->maximum;
    }

    /**
     * @param float $data
     * @return bool
     */
    public function validateMinimum(float $data): bool
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
        return self::NUMERIC_TYPE;
    }
}