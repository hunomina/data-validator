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
     * @var bool $null
     * Can the value be null in the associated schema
     */
    protected $null = false;

    /**
     * @var bool $isOptionnal
     * Can the value be optional in the associated schema
     */
    protected $optional = false;

    /**
     * @var null|int $length
     * `null` if if length does have to be checked
     */
    protected $length;

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
        return $this->null;
    }

    /**
     * @param bool $null
     * @return Rule
     */
    public function setNull(bool $null): Rule
    {
        $this->null = $null;
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
     * @param string $type
     * @return bool
     * Does a specific type can be length checked
     */
    public static function isTypeWithLengthCheck(string $type): bool
    {
        return $type === 'string' || in_array($type, self::TYPED_ARRAY_TYPES, true);
    }

    /**
     * @param $data
     * @return bool
     */
    public function validate($data): bool
    {
        if ($this->null && $data === null) {
            return true;
        }

        if (in_array($this->type, self::NUMERIC_TYPE, true)) {
            return is_numeric($data) && !is_string($data);
        }

        if (in_array($this->type, self::ARRAY_TYPES, true)) {
            return is_array($data) && ($this->length !== null ? count($data) === $this->length : true);
        }

        if (in_array($this->type, self::INT_STRICT_TYPES, true)) {
            return is_int($data);
        }

        if (in_array($this->type, self::FLOAT_STRICT_TYPES, true)) {
            return is_float($data);
        }

        if (in_array($this->type, self::BOOLEAN_TYPES, true)) {
            return is_bool($data);
        }

        if (in_array($this->type, self::CHAR_TYPES, true)) {
            return is_string($data) && strlen($data) === 1;
        }

        if (in_array($this->type, self::TYPED_ARRAY_TYPES, true)) {
            return $this->checkTypedList($data) && ($this->length !== null ? count($data) === $this->length : true);
        }

        if ($this->type === 'string') {
            return is_string($data) && ($this->length !== null ? strlen($data) === $this->length : true);
        }

        if (in_array($this->type, self::OBJECT_TYPES, true)) { // json object is a non empty array
            return is_array($data) && !empty($data) && ($this->length !== null ? count($data) === $this->length : true);
        }

        return false;
    }

    /**
     * @param $data
     * @return bool
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
                $rule = (new self())->setType('char');
                break;
            case 'string-list':
                $rule = (new self())->setType('string');
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