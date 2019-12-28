<?php

namespace hunomina\Validator\Json\Rule;

use DateTime;
use hunomina\Validator\Json\Exception\InvalidDataException;

class JsonRule implements Rule
{
    /* Scalar types */
    public const STRING_TYPE = 'string';
    public const INTEGER_TYPE = 'integer';
    public const FLOAT_TYPE = 'float';
    public const BOOLEAN_TYPE = 'boolean';
    public const CHAR_TYPE = 'character';
    public const NUMERIC_TYPE = 'numeric';

    /* List Types */
    public const NUMERIC_LIST_TYPE = 'numeric-list';
    public const STRING_LIST_TYPE = 'string-list';
    public const BOOLEAN_LIST_TYPE = 'boolean-list';
    public const INTEGER_LIST_TYPE = 'integer-list';
    public const FLOAT_LIST_TYPE = 'float-list';
    public const CHAR_LIST_TYPE = 'character-list';

    public const TYPED_ARRAY_TYPES = [
        self::NUMERIC_LIST_TYPE, self::STRING_LIST_TYPE, self::BOOLEAN_LIST_TYPE,
        self::INTEGER_LIST_TYPE, self::FLOAT_LIST_TYPE, self::CHAR_LIST_TYPE
    ];

    /* Complex types */
    public const LIST_TYPE = 'list';
    public const OBJECT_TYPE = 'object';

    /**
     * @var string $type
     * Type of the data in the associated schema
     */
    protected string $type;

    /**
     * @var bool $nullable
     * Can the value be null in the associated schema
     */
    protected bool $nullable = false;

    /**
     * @var bool $isOptionnal
     * Is the value optional in the associated schema
     */
    protected bool $optional = false;

    /**
     * @var null|int $length
     * `null` if length does have to be checked
     */
    protected ?int $length = null;

    /**
     * @var null|int $min
     * Number : minimum value
     * List : minimum size
     */
    protected ?int $min = null;

    /**
     * @var null|int $max
     * Number : maximum value
     * List : maximum site
     */
    protected ?int $max = null;

    /**
     * @var null|string
     * `null` if pattern does not have to be checked
     */
    protected ?string $pattern = null;

    /**
     * @var null|array $enum
     * `null` if no in_array($value, $enum) check needed
     */
    protected ?array $enum = null;

    /**
     * @var null|string $dateFormat
     * Date format of the data
     */
    protected ?string $dateFormat = null;

    /**
     * @var bool $empty
     * Is the data allowed to be empty
     */
    protected bool $empty = false;

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Rule
     * @codeCoverageIgnore
     */
    public function setType(string $type): Rule
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return bool
     * @codeCoverageIgnore
     */
    public function canBeNull(): bool
    {
        return $this->nullable;
    }

    /**
     * @param bool $null
     * @return Rule
     * @codeCoverageIgnore
     */
    public function setNullable(bool $null): Rule
    {
        $this->nullable = $null;
        return $this;
    }

    /**
     * @return bool
     * @codeCoverageIgnore
     */
    public function isOptional(): bool
    {
        return $this->optional;
    }

    /**
     * @param bool $isOptional
     * @return Rule
     * @codeCoverageIgnore
     */
    public function setOptional(bool $isOptional): Rule
    {
        $this->optional = $isOptional;
        return $this;
    }

    /**
     * @return null|int
     * `null` if if length does have to be checked
     * @codeCoverageIgnore
     */
    public function getLength(): ?int
    {
        return $this->length;
    }

    /**
     * @param int $length
     * @return Rule
     * `null` if if length does have to be checked
     * @codeCoverageIgnore
     */
    public function setLength(?int $length): Rule
    {
        $this->length = $length;
        return $this;
    }

    /**
     * @return string|null
     * @codeCoverageIgnore
     */
    public function getPattern(): ?string
    {
        return $this->pattern;
    }

    /**
     * @param string|null $pattern
     * @return JsonRule
     * @codeCoverageIgnore
     */
    public function setPattern(?string $pattern): Rule
    {
        $this->pattern = $pattern;
        return $this;
    }

    /**
     * @return int|null
     * @codeCoverageIgnore
     */
    public function getMin(): ?int
    {
        return $this->min;
    }

    /**
     * @param int|null $min
     * @return JsonRule
     * @codeCoverageIgnore
     */
    public function setMin(?int $min): Rule
    {
        $this->min = $min;
        return $this;
    }

    /**
     * @return int|null
     * @codeCoverageIgnore
     */
    public function getMax(): ?int
    {
        return $this->max;
    }

    /**
     * @param int|null $max
     * @return JsonRule
     * @codeCoverageIgnore
     */
    public function setMax(?int $max): Rule
    {
        $this->max = $max;
        return $this;
    }

    /**
     * @return array
     * @codeCoverageIgnore
     */
    public function getEnum(): ?array
    {
        return $this->enum;
    }

    /**
     * @param array|null $enum
     * @return Rule
     * @codeCoverageIgnore
     */
    public function setEnum(?array $enum): Rule
    {
        $this->enum = $enum;
        return $this;
    }

    /**
     * @return string|null
     * `null` if does not have to be checked
     * Date format to test the data with
     * @codeCoverageIgnore
     */
    public function getDateFormat(): ?string
    {
        return $this->dateFormat;
    }

    /**
     * @param string|null $dateFormat
     * @return Rule
     * @codeCoverageIgnore
     */
    public function setDateFormat(?string $dateFormat): Rule
    {
        $this->dateFormat = $dateFormat;
        return $this;
    }

    /**
     * @return bool
     */
    public function canBeEmpty(): bool
    {
        return $this->empty;
    }

    /**
     * @param bool $empty
     * @return JsonRule
     */
    public function setEmpty(bool $empty): JsonRule
    {
        $this->empty = $empty;
        return $this;
    }

    /**
     * @param string $type
     * @return bool
     * Does a specific type can be length checked
     */
    public static function isTypeWithLengthCheck(string $type): bool
    {
        return $type === self::STRING_TYPE || in_array($type, self::TYPED_ARRAY_TYPES, true);
    }

    /**
     * @param string $type
     * @return bool
     * Does a specific type can be pattern checked
     */
    public static function isTypeWithPatternCheck(string $type): bool
    {
        return $type === self::STRING_TYPE || $type === self::STRING_LIST_TYPE || $type === self::CHAR_TYPE || $type === self::CHAR_LIST_TYPE;
    }

    /**
     * @param string $type
     * @return bool
     */
    public static function isTypeWithMinMaxCheck(string $type): bool
    {
        return $type === self::NUMERIC_TYPE
            || $type === self::INTEGER_TYPE
            || $type === self::FLOAT_TYPE
            || in_array($type, self::TYPED_ARRAY_TYPES, true);
    }

    /**
     * @param string $type
     * @return bool
     */
    public static function isTypeWithEnumCheck(string $type): bool
    {
        return $type === self::STRING_TYPE
            || $type === self::INTEGER_TYPE
            || $type === self::FLOAT_TYPE
            || $type === self::CHAR_TYPE
            || in_array($type, self::TYPED_ARRAY_TYPES, true);
    }

    /**
     * @param string $type
     * @return bool
     */
    public static function isTypeWithDateFormatCheck(string $type): bool
    {
        // todo : add date format check for string typed list
        return $type === self::STRING_TYPE;
    }

    public static function isTypeWithEmptyCheck(string $type): bool
    {
        return $type === self::STRING_TYPE
            || in_array($type, self::TYPED_ARRAY_TYPES, true);
    }

    /**
     * @param $data
     * @return bool
     * @throws InvalidDataException
     */
    public function validate($data): bool
    {
        if ($data === null) {
            if ($this->nullable) {
                return true;
            }
            throw new InvalidDataException('Can not be null', InvalidDataException::NULL_VALUE_NOT_ALLOWED);
        }

        if ($this->type === self::STRING_TYPE) {
            return $this->isValidString($data);
        }

        if ($this->type === self::NUMERIC_TYPE) {
            return $this->isValidNumber($data);
        }

        if ($this->type === self::INTEGER_TYPE) {
            return $this->isValidInteger($data);
        }

        if ($this->type === self::FLOAT_TYPE) {
            return $this->isValidFloat($data);
        }

        if ($this->type === self::BOOLEAN_TYPE) {
            return $this->isValidBoolean($data);
        }

        if ($this->type === self::CHAR_TYPE) {
            return $this->isValidCharacter($data);
        }

        if ($this->type === self::OBJECT_TYPE) {
            return $this->isValidObject($data);
        }

        if ($this->type === self::LIST_TYPE) {
            return $this->isValidList($data);
        }

        if (in_array($this->type, self::TYPED_ARRAY_TYPES, true)) {
            return $this->isValidTypedList($data);
        }

        throw new InvalidDataException('Unknown data type', InvalidDataException::UNKNOWN_DATA_TYPE);
    }

    /**
     * @param $data
     * @return bool
     * @throws InvalidDataException
     */
    protected function isValidString($data): bool
    {
        if (!is_string($data)) {
            throw new InvalidDataException('Must be a string', InvalidDataException::INVALID_DATA_TYPE);
        }

        if ($this->empty === false && $data === '') {
            throw new InvalidDataException('Can not be empty', InvalidDataException::EMPTY_VALUE_NOT_ALLOWED);
        }

        if ($this->length !== null && strlen($data) !== $this->length) {
            throw new InvalidDataException('Invalid length: Must be ' . $this->length . '. Is ' . strlen($data), InvalidDataException::INVALID_LENGTH);
        }

        if ($this->pattern !== null && !preg_match($this->pattern, $data)) {
            throw new InvalidDataException('Must match the pattern : `' . $this->pattern . '`', InvalidDataException::PATTERN_NOT_MATCHED);
        }

        if ($this->enum !== null && !in_array($data, $this->enum, true)) {
            throw new InvalidDataException('Must be one of the following values : ' . implode(', ', $this->enum), InvalidDataException::UNAUTHORIZED_VALUE);
        }

        if ($this->dateFormat !== null) {
            $d = DateTime::createFromFormat($this->dateFormat, $data);
            if (!($d instanceof DateTime) || $d->format($this->dateFormat) !== $data) {
                throw new InvalidDataException("Must match the '" . $this->dateFormat . "' date format. See available format here : https://www.php.net/manual/fr/datetime.createfromformat.php", InvalidDataException::INVALID_DATE_FORMAT);
            }
        }

        return true;
    }

    /**
     * @param $data
     * @return bool
     * @throws InvalidDataException
     */
    protected function isValidInteger($data): bool
    {
        if (!is_int($data)) {
            throw new InvalidDataException('Must be an integer', InvalidDataException::INVALID_DATA_TYPE);
        }

        if ($this->min !== null && $data < $this->min) {
            throw new InvalidDataException('Must be higher than ' . $this->min, InvalidDataException::INVALID_MIN_VALUE);
        }

        if ($this->max !== null && $data > $this->max) {
            throw new InvalidDataException('Must be lower than ' . $this->max, InvalidDataException::INVALID_MAX_VALUE);
        }

        if ($this->enum !== null && !in_array($data, $this->enum, true)) {
            throw new InvalidDataException('Must be one of the following values : ' . implode(', ', $this->enum), InvalidDataException::UNAUTHORIZED_VALUE);
        }

        return true;
    }

    /**
     * @param $data
     * @return bool
     * @throws InvalidDataException
     */
    protected function isValidFloat($data): bool
    {
        if (!is_float($data)) {
            throw new InvalidDataException('Must be a floating number', InvalidDataException::INVALID_DATA_TYPE);
        }

        if ($this->min !== null && $data < $this->min) {
            throw new InvalidDataException('Must be higher than ' . $this->min, InvalidDataException::INVALID_MIN_VALUE);
        }

        if ($this->max !== null && $data > $this->max) {
            throw new InvalidDataException('Must be lower than ' . $this->max, InvalidDataException::INVALID_MAX_VALUE);
        }

        if ($this->enum !== null && !in_array($data, $this->enum, true)) {
            throw new InvalidDataException('Must be one of the following values : ' . implode(', ', $this->enum), InvalidDataException::UNAUTHORIZED_VALUE);
        }

        return true;
    }

    /**
     * @param $data
     * @return bool
     * @throws InvalidDataException
     */
    protected function isValidNumber($data): bool
    {
        if (!is_numeric($data) || is_string($data)) {
            throw new InvalidDataException('Must be a non string number', InvalidDataException::INVALID_DATA_TYPE);
        }

        if ($this->min !== null && $data < $this->min) {
            throw new InvalidDataException('Must be higher than ' . $this->min, InvalidDataException::INVALID_MIN_VALUE);
        }

        if ($this->max !== null && $data > $this->max) {
            throw new InvalidDataException('Must be lower than ' . $this->max, InvalidDataException::INVALID_MAX_VALUE);
        }

        if ($this->enum !== null && !in_array($data, $this->enum, true)) {
            throw new InvalidDataException('Must be one of the following values : ' . implode(', ', $this->enum), InvalidDataException::UNAUTHORIZED_VALUE);
        }

        return true;
    }

    /**
     * @param $data
     * @return bool
     * @throws InvalidDataException
     */
    protected function isValidTypedList($data): bool
    {
        if (!is_array($data)) {
            throw new InvalidDataException('Must be an array', InvalidDataException::INVALID_DATA_TYPE);
        }

        $length = count($data);

        if ($this->empty === false && $length === 0) {
            throw new InvalidDataException('Can not be empty', InvalidDataException::EMPTY_VALUE_NOT_ALLOWED);
        }

        if ($this->length !== null && $length !== $this->length) {
            throw new InvalidDataException('Invalid length: Must be ' . $this->length . '. Is ' . $length, InvalidDataException::INVALID_LENGTH);
        }

        if ($this->min !== null && $length < $this->min) {
            throw new InvalidDataException('Must contain at least ' . $this->min . ' elements', InvalidDataException::INVALID_MIN_VALUE);
        }

        if ($this->max !== null && $length > $this->max) {
            throw new InvalidDataException('Must contain at most ' . $this->max . ' elements', InvalidDataException::INVALID_MAX_VALUE);
        }

        return $this->checkTypedListValues($data, $this->enum);
    }

    /**
     * @param $data
     * @return bool
     * @throws InvalidDataException
     */
    protected function isValidCharacter($data): bool
    {
        if (!is_string($data) || strlen($data) !== 1) {
            throw new InvalidDataException('Must be a character', InvalidDataException::INVALID_DATA_TYPE);
        }

        if ($this->pattern !== null && !preg_match($this->pattern, $data)) {
            throw new InvalidDataException('Must match the pattern ' . $this->pattern, InvalidDataException::PATTERN_NOT_MATCHED);
        }

        if ($this->enum !== null && !in_array($data, $this->enum, true)) {
            throw new InvalidDataException('Must be one of the following values : ' . implode(', ', $this->enum), InvalidDataException::UNAUTHORIZED_VALUE);
        }

        return true;
    }

    /**
     * @param $data
     * @return bool
     * @throws InvalidDataException
     */
    protected function isValidBoolean($data): bool
    {
        if (!is_bool($data)) {
            throw new InvalidDataException('Must be a boolean', InvalidDataException::INVALID_DATA_TYPE);
        }
        return true;
    }

    /**
     * @param $data
     * @return bool
     * @throws InvalidDataException
     */
    protected function isValidList($data): bool
    {
        if (!is_array($data)) {
            throw new InvalidDataException('Must be an array', InvalidDataException::INVALID_DATA_TYPE);
        }
        return true;
    }

    /**
     * @param $data
     * @return bool
     * @throws InvalidDataException
     */
    protected function isValidObject($data): bool
    {
        if (!is_array($data) || empty($data)) {
            throw new InvalidDataException('Must be a non empty array', InvalidDataException::INVALID_DATA_TYPE);
        }
        return true;
    }

    /**
     * @param $data
     * @param array|null $enum
     * @return bool
     * @throws InvalidDataException
     */
    protected function checkTypedListValues($data, ?array $enum): bool
    {
        $rule = null;
        switch ($this->type) {
            case self::INTEGER_LIST_TYPE:
                $rule = (new self())->setType(self::INTEGER_TYPE);
                break;
            case self::FLOAT_LIST_TYPE:
                $rule = (new self())->setType(self::FLOAT_TYPE);
                break;
            case self::BOOLEAN_LIST_TYPE:
                $rule = (new self())->setType(self::BOOLEAN_TYPE);
                break;
            case self::CHAR_LIST_TYPE:
                $rule = (new self())->setType(self::CHAR_TYPE)->setPattern($this->pattern);
                break;
            case self::STRING_LIST_TYPE:
                $rule = (new self())->setType(self::STRING_TYPE)->setPattern($this->pattern);
                break;
            case self::NUMERIC_LIST_TYPE:
                $rule = (new self())->setType(self::NUMERIC_TYPE);
                break;
        }

        if (!($rule instanceof self)) {
            throw new InvalidDataException('Invalid typed list type', InvalidDataException::UNKNOWN_DATA_TYPE);
        }

        $rule->setEnum($enum); // set $enum to check if each element of the typed list is in $enum

        foreach ($data as $key => $value) {
            try {
                $rule->validate($value);
            } catch (InvalidDataException $e) {
                throw new InvalidDataException('Element at index ' . $key . ' is invalid : ' . $e->getMessage(), InvalidDataException::INVALID_TYPED_LIST_ELEMENT, $e);
            }
        }

        return true;
    }
}