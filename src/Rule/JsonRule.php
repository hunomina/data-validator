<?php

namespace hunomina\Validator\Json\Rule;

use DateTime;

class JsonRule implements Rule
{
    public const LIST_TYPES = ['list', 'array'];
    public const INT_STRICT_TYPES = ['int', 'integer', 'long'];
    public const FLOAT_STRICT_TYPES = ['float', 'double'];
    public const NUMERIC_TYPE = ['numeric', 'number'];
    public const BOOLEAN_TYPES = ['boolean', 'bool'];
    public const CHAR_TYPES = ['char', 'character'];
    public const TYPED_ARRAY_TYPES = ['numeric-list', 'string-list', 'boolean-list', 'integer-list', 'float-list', 'char-list'];
    public const OBJECT_TYPES = ['entity', 'object'];

    /**
     * @var string $type
     * Type of the data in the associated schema
     */
    protected $type;

    /**
     * @var bool $nullable
     * Can the value be null in the associated schema
     */
    protected $nullable = false;

    /**
     * @var bool $isOptionnal
     * Is the value optional in the associated schema
     */
    protected $optional = false;

    /**
     * @var null|int $length
     * `null` if length does have to be checked
     */
    protected $length;

    /**
     * @var null|int $min
     * Number : minimum value
     * List : minimum size
     */
    protected $min;

    /**
     * @var null|int $max
     * Number : maximum value
     * List : maximum site
     */
    protected $max;

    /**
     * @var null|string
     * `null` if pattern does not have to be checked
     */
    protected $pattern;

    /**
     * @var null|array $enum
     * `null` if no in_array($value, $enum) check needed
     */
    protected $enum;

    /**
     * @var null|string $error
     * Error message when the data does not match the rule
     */
    protected $error;

    /**
     * @var null|string $dateFormat
     * Date format of the data
     */
    protected $dateFormat;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Rule
     */
    public function setType(string $type): Rule
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return bool
     */
    public function canBeNull(): bool
    {
        return $this->nullable;
    }

    /**
     * @param bool $null
     * @return Rule
     */
    public function setNullable(bool $null): Rule
    {
        $this->nullable = $null;
        return $this;
    }

    /**
     * @return bool
     */
    public function isOptional(): bool
    {
        return $this->optional;
    }

    /**
     * @param bool $isOptional
     * @return Rule
     */
    public function setOptional(bool $isOptional): Rule
    {
        $this->optional = $isOptional;
        return $this;
    }

    /**
     * @return null|int
     * `null` if if length does have to be checked
     */
    public function getLength(): ?int
    {
        return $this->length;
    }

    /**
     * @param int $length
     * @return Rule
     * `null` if if length does have to be checked
     */
    public function setLength(?int $length): Rule
    {
        $this->length = $length;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPattern(): ?string
    {
        return $this->pattern;
    }

    /**
     * @param string|null $pattern
     * @return JsonRule
     */
    public function setPattern(?string $pattern): Rule
    {
        $this->pattern = $pattern;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMin(): ?int
    {
        return $this->min;
    }

    /**
     * @param int|null $min
     * @return JsonRule
     */
    public function setMin(?int $min): Rule
    {
        $this->min = $min;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMax(): ?int
    {
        return $this->max;
    }

    /**
     * @param int|null $max
     * @return JsonRule
     */
    public function setMax(?int $max): Rule
    {
        $this->max = $max;
        return $this;
    }

    /**
     * @return array
     */
    public function getEnum(): ?array
    {
        return $this->enum;
    }

    /**
     * @param array|null $enum
     * @return Rule
     */
    public function setEnum(?array $enum): Rule
    {
        $this->enum = $enum;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getError(): ?string
    {
        return $this->error;
    }

    /**
     * @param string|null $error
     * @return JsonRule
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
     */
    public function getDateFormat(): ?string
    {
        return $this->dateFormat;
    }

    /**
     * @param string|null $dateFormat
     * @return Rule
     */
    public function setDateFormat(?string $dateFormat): Rule
    {
        $this->dateFormat = $dateFormat;
        return $this;
    }

    /**
     * @param string $type
     * @return bool
     * Does a specific type can be length checked
     */
    public static function isTypeWithLengthCheck(string $type): bool
    {
        return $type === 'string' || in_array($type, self::TYPED_ARRAY_TYPES, true);
    }

    /**
     * @param string $type
     * @return bool
     * Does a specific type can be pattern checked
     */
    public static function isTypeWithPatternCheck(string $type): bool
    {
        return $type === 'string' || $type === 'string-list' || $type === 'char-list' || in_array($type, self::CHAR_TYPES, true);
    }

    /**
     * @param string $type
     * @return bool
     */
    public static function isTypeWithMinMaxCheck(string $type): bool
    {
        return in_array($type, self::NUMERIC_TYPE, true)
            || in_array($type, self::INT_STRICT_TYPES, true)
            || in_array($type, self::FLOAT_STRICT_TYPES, true)
            || in_array($type, self::TYPED_ARRAY_TYPES, true)
            || in_array($type, self::LIST_TYPES, true);
    }

    /**
     * @param string $type
     * @return bool
     */
    public static function isTypeWithEnumCheck(string $type): bool
    {
        return $type === 'string'
            || in_array($type, self::INT_STRICT_TYPES, true)
            || in_array($type, self::FLOAT_STRICT_TYPES, true)
            || in_array($type, self::CHAR_TYPES, true)
            || in_array($type, self::TYPED_ARRAY_TYPES, true);
    }

    /**
     * @param string $type
     * @return bool
     */
    public static function isTypeWithDateFormatCheck(string $type): bool
    {
        return $type === 'string';
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

        if (in_array($this->type, self::NUMERIC_TYPE, true)) {
            return $this->isValidNumber($data);
        }

        if (in_array($this->type, self::LIST_TYPES, true)) {
            return $this->isValidList($data);
        }

        if (in_array($this->type, self::INT_STRICT_TYPES, true)) {
            return $this->isValidInteger($data);
        }

        if (in_array($this->type, self::FLOAT_STRICT_TYPES, true)) {
            return $this->isValidFloat($data);
        }

        if (in_array($this->type, self::BOOLEAN_TYPES, true)) {
            return $this->isValidBoolean($data);
        }

        if (in_array($this->type, self::CHAR_TYPES, true)) {
            return $this->isValidCharacter($data);
        }

        if (in_array($this->type, self::TYPED_ARRAY_TYPES, true)) {
            return $this->isValidTypedList($data);
        }

        if ($this->type === 'string') {
            return $this->isValidString($data);
        }

        if (in_array($this->type, self::OBJECT_TYPES, true)) {
            return $this->isValidObject($data);
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

        if ($this->length !== null && strlen($data) !== $this->length) {
            $this->error = 'Invalid length: Must be ' . $this->length . '. Is ' . strlen($data);
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
                $this->error = "Must match the '" . $this->dateFormat . "' date format";
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
            $this->error = 'Must be a non string numeric number';
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

        if (!$this->checkTypedListValues($data, $this->enum)) {
            $this->error = 'Invalid elements for a ' . $this->type;
            if ($this->enum !== null) {
                $this->error .= ' Only those elements can be in the list : ' . implode(', ', $this->enum);
            }
            return false;
        }

        if ($this->length !== null && count($data) !== $this->length) {
            $this->error = 'Invalid length: Must be ' . $this->length . '. Is ' . count($data);
            return false;
        }

        if ($this->min !== null && count($data) < $this->min) {
            $this->error = 'Must contain at least ' . $this->min . ' elements';
            return false;
        }

        if ($this->max !== null && count($data) > $this->max) {
            $this->error = 'Must contain at most ' . $this->max . ' elements';
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
     * $data is not typed in order to prevent exceptions
     */
    protected function checkTypedListValues($data, ?array $enum): bool
    {
        $rule = null;
        switch ($this->type) {
            case 'integer-list':
                $rule = (new self())->setType('integer');
                break;
            case 'float-list':
                $rule = (new self())->setType('float');
                break;
            case 'boolean-list':
                $rule = (new self())->setType('boolean');
                break;
            case 'char-list':
                $rule = (new self())->setType('char')->setPattern($this->pattern);
                break;
            case 'string-list':
                $rule = (new self())->setType('string')->setPattern($this->pattern);
                break;
            case 'numeric-list':
                $rule = (new self())->setType('numeric');
                break;
        }

        if (!($rule instanceof self)) {
            return false;
        }

        $rule->setEnum($enum); // set $enum to check if each element of the typed list is in $enum

        foreach ($data as $value) {
            if (!$rule->validate($value)) {
                return false;
            }
        }

        return true;
    }
}