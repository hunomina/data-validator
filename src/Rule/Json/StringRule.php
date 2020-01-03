<?php

namespace hunomina\Validator\Json\Rule\Json;

use hunomina\Validator\Json\Exception\Json\InvalidDataException;
use hunomina\Validator\Json\Rule\Json\Traits\DateFormatCheckTrait;
use hunomina\Validator\Json\Rule\Json\Traits\EmptyCheckTrait;
use hunomina\Validator\Json\Rule\Json\Traits\EnumCheckTrait;
use hunomina\Validator\Json\Rule\Json\Traits\LengthCheckTrait;
use hunomina\Validator\Json\Rule\Json\Traits\NullCheckTrait;
use hunomina\Validator\Json\Rule\Json\Traits\PatternCheckTrait;

class StringRule extends JsonRule
{
    use NullCheckTrait;
    use LengthCheckTrait;
    use PatternCheckTrait;
    use EnumCheckTrait;
    use DateFormatCheckTrait;
    use EmptyCheckTrait;

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

        if (!is_string($data)) {
            throw new InvalidDataException('Must be a string', InvalidDataException::INVALID_DATA_TYPE);
        }

        if (!$this->validateEmptyness($data)) {
            throw new InvalidDataException('Can not be empty', InvalidDataException::EMPTY_VALUE_NOT_ALLOWED);
        }

        if (!$this->validateLength($data)) {
            throw new InvalidDataException('Invalid length: Must be ' . $this->length . '. Is ' . strlen($data), InvalidDataException::INVALID_LENGTH);
        }

        if (!$this->validatePattern($data)) {
            throw new InvalidDataException('Must match the pattern : `' . $this->pattern . '`', InvalidDataException::PATTERN_NOT_MATCHED);
        }

        if (!$this->validateEnum($data)) {
            throw new InvalidDataException('Must be one of the following values : ' . implode(', ', $this->enum), InvalidDataException::UNAUTHORIZED_VALUE);
        }

        if (!$this->validateDateFormat($data)) {
            throw new InvalidDataException("Must match the '" . $this->dateFormat . "' date format. See available formats here : https://www.php.net/manual/fr/datetime.createfromformat.php", InvalidDataException::INVALID_DATE_FORMAT);
        }

        return true;
    }

    /**
     * @param $data
     * @return bool
     */
    public function validateEmptyness(string $data): bool
    {
        if ($this->empty === false) { // can not be an empty value
            return $data !== '';
        }

        return true;
    }

    /**
     * @param $data
     * @return bool
     */
    public function validateLength(string $data): bool
    {
        if ($this->length === null) {
            return true;
        }

        return strlen($data) === $this->length;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::STRING_TYPE;
    }
}