<?php

namespace hunomina\Validator\Json\Rule;

class JsonRule implements Rule
{
    public const ARRAY_TYPES = ['list', 'array'];
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
     * @var array
     * Is in_array($value, $in, true)
     */
    protected $in = [];

    /**
     * @var null|string
     * `null` if pattern does not have to be checked
     */
    protected $pattern;

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
     * @param $data
     * @return bool
     */
    public function validate($data): bool
    {
        if ($this->nullable && $data === null) {
            return true;
        }

        if (in_array($this->type, self::NUMERIC_TYPE, true)) {
            return is_numeric($data) && !is_string($data)
                && ($this->min !== null ? $data >= $this->min : true)
                && ($this->max !== null ? $data <= $this->max : true);
        }

        if (in_array($this->type, self::ARRAY_TYPES, true)) {
            return is_array($data);
        }

        if (in_array($this->type, self::INT_STRICT_TYPES, true)) {
            return is_int($data)
                && ($this->min !== null ? $data >= $this->min : true)
                && ($this->max !== null ? $data <= $this->max : true);
        }

        if (in_array($this->type, self::FLOAT_STRICT_TYPES, true)) {
            return is_float($data)
                && ($this->min !== null ? $data >= $this->min : true)
                && ($this->max !== null ? $data <= $this->max : true);
        }

        if (in_array($this->type, self::BOOLEAN_TYPES, true)) {
            return is_bool($data);
        }

        if (in_array($this->type, self::CHAR_TYPES, true)) {
            // pattern can be used to match a range of characters
            return is_string($data)
                && strlen($data) === 1
                && ($this->pattern !== null ? preg_match($this->pattern, $data) : true);
        }

        if (in_array($this->type, self::TYPED_ARRAY_TYPES, true)) {
            return $this->checkTypedList($data)
                && ($this->length !== null ? count($data) === $this->length : true)
                && ($this->min !== null ? count($data) >= $this->min : true)
                && ($this->max !== null ? count($data) <= $this->max : true);
        }

        if ($this->type === 'string') {
            return is_string($data)
                && ($this->length !== null ? strlen($data) === $this->length : true)
                && ($this->pattern !== null ? preg_match($this->pattern, $data) : true);
        }

        if (in_array($this->type, self::OBJECT_TYPES, true)) {
            // json object is a non empty array
            return is_array($data) && !empty($data);
        }

        return false;
    }

    /**
     * @param $data
     * @return bool
     * $data is not typed in order to prevent exceptions
     */
    protected function checkTypedList($data): bool
    {
        if (!is_array($data)) {
            return false;
        }

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
                $rule = (new self())->setPattern($this->pattern)->setType('char');
                break;
            case 'string-list':
                $rule = (new self())->setPattern($this->pattern)->setType('string');
                break;
            case 'numeric-list':
                $rule = (new self())->setType('numeric');
                break;
        }

        if (!($rule instanceof self)) {
            return false;
        }

        foreach ($data as $value) {
            if (!$rule->validate($value)) {
                return false;
            }
        }

        return true;
    }
}