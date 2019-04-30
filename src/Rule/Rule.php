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
    public const TYPED_ARRAY_TYPES = ['numeric-list', 'string-list', 'boolean-list', 'integer-list', 'float-list', 'char-list'];

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
            return self::isChar($data);
        }

        if (in_array($this->type, self::TYPED_ARRAY_TYPES, true)) {
            return $this->checkTypedList($data);
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

    /**
     * @param $data
     * @return bool
     */
    protected static function isChar($data): bool
    {
        return is_string($data) && strlen($data) === 1;
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

        $typeCheckerFunction = null;

        switch ($this->type) {
            case 'integer-list':
                $typeCheckerFunction = static function ($data): bool {
                    return is_int($data);
                };
                break;
            case 'float-list':
                $typeCheckerFunction = static function ($data): bool {
                    return is_float($data);
                };
                break;
            case 'boolean-list':
                $typeCheckerFunction = static function ($data): bool {
                    return is_bool($data);
                };
                break;
            case 'char-list':
                $typeCheckerFunction = static function ($data): bool {
                    return self::isChar($data);
                };
                break;
            case 'string-list':
                $typeCheckerFunction = static function ($data): bool {
                    return is_string($data);
                };
                break;
            case 'numeric-list':
                $typeCheckerFunction = static function ($data): bool {
                    return is_numeric($data) && !is_string($data);
                };
                break;
        }

        if (!is_callable($typeCheckerFunction)) {
            return false;
        }

        foreach ($data as $value) {
            if (!$typeCheckerFunction($value)) {
                return false;
            }
        }

        return true;
    }
}