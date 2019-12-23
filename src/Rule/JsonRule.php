<?php

namespace hunomina\Validator\Json\Rule;

use DateTime;

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
     * @var string|null $error
     * Error message when the data does not match the rule
     */
    protected ?string $error = null;

    /**
     * @var null|string $dateFormat
     * Date format of the data
     */
    protected ?string $dateFormat = null;

    /**
     * @var bool $empty
     * Is the data allowed to be empty
     */
    protected bool $empty = true;

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
     * @codeCoverageIgnore
     */
    public function getError(): ?string
    {
        return $this->error;
    }

    /**
     * @param string|null $error
     * @return JsonRule
     * @codeCoverageIgnore
     */
    public function setError(?string $error): Rule
    {
        $this->error = $error;
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
     */
    public function validate($data): bool
    {
        if ($this->nullable && $data === null) {
            return true;
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

        $this->error = 'Invalid type to check';
        return false;
    }

    /**
     * @param $data
     * @return bool
     */
    protected function isValidString($data): bool
    {
        if (!is_string($data)) {
            $this->error = 'Must be a string';
            return false;
        }

        if ($this->empty === false && $data === '') {
            $this->error = 'Invalid string : Can not be empty';
            return false;
        }

        if ($this->length !== null && strlen($data) !== $this->length) {
            $this->error = 'Invalid string length: Must be ' . $this->length . '. Is ' . strlen($data);
            return false;
        }

        if ($this->pattern !== null && !preg_match($this->pattern, $data)) {
            $this->error = 'Must match the pattern ' . $this->pattern;
            return false;
        }

        if ($this->enum !== null && !in_array($data, $this->enum, true)) {
            $this->error = 'Must be one of the following values : ' . implode(', ', $this->enum);
            return false;
        }

        if ($this->dateFormat !== null) {
            $d = DateTime::createFromFormat($this->dateFormat, $data);
            if (!($d instanceof DateTime) || $d->format($this->dateFormat) !== $data) {
                $this->error = "Must match the '" . $this->dateFormat . "' date format. See available format here : https://www.php.net/manual/fr/datetime.createfromformat.php";
                return false;
            }
        }

        return true;
    }

    /**
     * @param $data
     * @return bool
     */
    protected function isValidInteger($data): bool
    {
        if (!is_int($data)) {
            $this->error = 'Must be an integer';
            return false;
        }

        if ($this->min !== null && $data < $this->min) {
            $this->error = 'Must be higher than ' . $this->min;
            return false;
        }

        if ($this->max !== null && $data > $this->max) {
            $this->error = 'Must be lower than ' . $this->max;
            return false;
        }

        if ($this->enum !== null && !in_array($data, $this->enum, true)) {
            $this->error = 'Must be one of the following values : ' . implode(', ', $this->enum);
            return false;
        }

        return true;
    }

    /**
     * @param $data
     * @return bool
     */
    protected function isValidFloat($data): bool
    {
        if (!is_float($data)) {
            $this->error = 'Must be a floating number';
            return false;
        }

        if ($this->min !== null && $data < $this->min) {
            $this->error = 'Must be higher than ' . $this->min;
            return false;
        }

        if ($this->max !== null && $data > $this->max) {
            $this->error = 'Must be lower than ' . $this->max;
            return false;
        }

        if ($this->enum !== null && !in_array($data, $this->enum, true)) {
            $this->error = 'Must be one of the following values : ' . implode(', ', $this->enum);
            return false;
        }

        return true;
    }

    /**
     * @param $data
     * @return bool
     */
    protected function isValidNumber($data): bool
    {
        if (!is_numeric($data) || is_string($data)) {
            $this->error = 'Must be a non string number';
            return false;
        }

        if ($this->min !== null && $data < $this->min) {
            $this->error = 'Must be higher than ' . $this->min;
            return false;
        }

        if ($this->max !== null && $data > $this->max) {
            $this->error = 'Must be lower than ' . $this->max;
            return false;
        }

        if ($this->enum !== null && !in_array($data, $this->enum, true)) {
            $this->error = 'Must be one of the following values : ' . implode(', ', $this->enum);
            return false;
        }

        return true;
    }

    /**
     * @param $data
     * @return bool
     */
    protected function isValidTypedList($data): bool
    {
        if (!is_array($data)) {
            $this->error = 'Must be an array';
            return false;
        }

        $length = count($data);

        if ($this->empty === false && $length === 0) {
            $this->error = 'Invalid list : Can not be empty';
            return false;
        }

        if ($this->length !== null && $length !== $this->length) {
            $this->error = 'Invalid list length: Must be ' . $this->length . '. Is ' . $length;
            return false;
        }

        if ($this->min !== null && $length < $this->min) {
            $this->error = 'Must contain at least ' . $this->min . ' elements';
            return false;
        }

        if ($this->max !== null && $length > $this->max) {
            $this->error = 'Must contain at most ' . $this->max . ' elements';
            return false;
        }

        if (!$this->checkTypedListValues($data, $this->enum)) {
            // $this->error is modified by $this->checkTypedListValues() in order to get the error message for the invalid element
            $this->error = 'Invalid elements for a ' . $this->type . '. ' . $this->error;
            return false;
        }

        return true;
    }

    /**
     * @param $data
     * @return bool
     */
    protected function isValidCharacter($data): bool
    {
        if (!is_string($data) || strlen($data) !== 1) {
            $this->error = 'Must be a character';
            return false;
        }

        if ($this->pattern !== null && !preg_match($this->pattern, $data)) {
            $this->error = 'Must match the pattern ' . $this->pattern;
            return false;
        }

        if ($this->enum !== null && !in_array($data, $this->enum, true)) {
            $this->error = 'Must be one of the following values : ' . implode(', ', $this->enum);
            return false;
        }

        return true;
    }

    /**
     * @param $data
     * @return bool
     */
    protected function isValidBoolean($data): bool
    {
        if (!is_bool($data)) {
            $this->error = 'Must be a boolean';
            return false;
        }
        return true;
    }

    /**
     * @param $data
     * @return bool
     */
    protected function isValidList($data): bool
    {
        if (!is_array($data)) {
            $this->error = 'Must be an array';
        }
        return true;
    }

    /**
     * @param $data
     * @return bool
     */
    protected function isValidObject($data): bool
    {
        if (!is_array($data) || empty($data)) {
            $this->error = 'Must be a non empty array';
            return false;
        }
        return true;
    }

    /**
     * @param $data
     * @param array|null $enum
     * @return bool
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
            return false;
        }

        $rule->setEnum($enum); // set $enum to check if each element of the typed list is in $enum

        foreach ($data as $key => $value) {
            if (!$rule->validate($value)) {
                $this->error = 'Element at index ' . $key . ' is invalid : ' . $rule->getError();
                return false;
            }
        }

        return true;
    }
}