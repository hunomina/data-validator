<?php

namespace hunomina\DataValidator\Rule\Json;

use hunomina\DataValidator\Exception\Json\InvalidDataException;
use hunomina\DataValidator\Rule\Json\Traits\DateFormatCheckTrait;
use hunomina\DataValidator\Rule\Json\Traits\EmptyCheckTrait;
use hunomina\DataValidator\Rule\Json\Traits\EnumCheckTrait;
use hunomina\DataValidator\Rule\Json\Traits\LengthCheckTrait;
use hunomina\DataValidator\Rule\Json\Traits\NullCheckTrait;
use hunomina\DataValidator\Rule\Json\Traits\PatternCheckTrait;

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
        if ($data === null) {
            if (!$this->nullable) {
                throw new InvalidDataException('Can not be null', InvalidDataException::NULL_VALUE_NOT_ALLOWED);
            }
            return true;
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