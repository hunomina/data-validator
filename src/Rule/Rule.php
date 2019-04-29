<?php

namespace hunomina\Validator\Json\Rule;

abstract class Rule
{
    public const ARRAY_TYPES = ['list', 'array'];
    public const INT_STRICT_TYPES = ['int', 'long'];
    public const FLOAT_STRICT_TYPES = ['float', 'double'];
    public const NUMERIC_TYPE = ['numeric', 'number'];
    public const BOOLEAN_TYPES = ['boolean', 'bool'];
    public const CHAR_TYPES = ['char', 'character'];

    /**
     * @var bool $null
     * Can the value be null in the associated schema
     */
    protected $null = false;

    /**
     * @var string $type
     * Type of the data in the associated schema
     */
    protected $type;

    /**
     * @var bool $isOptionnal
     * Can the value be optional in the associated schema
     */
    protected $optional = false;

    public function validate($data): bool
    {
        if ($this->null && $data === null) {
            return true;
        }

        if (in_array($this->type, self::NUMERIC_TYPE, true)) {
            return is_numeric($data) && !is_string($data);
        }

        if (in_array($this->type, self::ARRAY_TYPES, true)) {
            return is_array($data);
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

        if ($this->type === 'string') {
            return is_string($data);
        }

        return false;
    }

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
    public function setType(string $type): self
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
    public function setNull(bool $null): self
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
    public function setOptional(bool $isOptional): self
    {
        $this->optional = $isOptional;
        return $this;
    }
}