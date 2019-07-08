<?php

namespace hunomina\Validator\Json\Schema;

use hunomina\Validator\Json\Data\DataType;
use hunomina\Validator\Json\Data\JsonData;
use hunomina\Validator\Json\Exception\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;

class JsonSchema implements DataSchema
{
    /** @var string $lastError */
    private $lastError;

    /** @var string $type */
    private $type = 'object';

    /** @var JsonRule[] $rule */
    private $rules = [];

    /** @var JsonSchema[] $children */
    private $children = [];

    /** @var bool $optional */
    private $optional = false;

    /** @var bool $nullable */
    private $nullable = false;

    /**
     * @return JsonRule[]
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    /**
     * @return JsonSchema[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @return bool
     */
    public function isOptional(): bool
    {
        return $this->optional;
    }

    /**
     * @param bool $optional
     * @return JsonSchema
     */
    public function setOptional(bool $optional): JsonSchema
    {
        $this->optional = $optional;
        return $this;
    }

    /**
     * @return bool
     */
    public function isNullable(): bool
    {
        return $this->nullable;
    }

    /**
     * @param bool $canBeNull
     * @return JsonSchema
     */
    public function setNullable(bool $canBeNull): JsonSchema
    {
        $this->nullable = $canBeNull;
        return $this;
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
     * @return JsonSchema
     */
    public function setType(string $type): JsonSchema
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     * When validate fail, an error must be set, not returned or thrown
     * User can access it using this method
     */
    public function getLastError(): ?string
    {
        return $this->lastError;
    }

    /**
     * @param DataType $dataType
     * @return bool
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    public function validate(DataType $dataType): bool
    {
        if (!($dataType instanceof JsonData)) {
            throw new InvalidDataTypeException('JsonSchema only check JsonData');
        }

        if ($dataType->getData() === null) {
            if (!$this->nullable) {
                $this->lastError = 'This data can not be `null`';
                return false;
            }
            return true;
        }

        // $dataType->getData() is an array
        if ($this->type === 'object') {
            return $this->validateObject($dataType);
        }

        if ($this->type === 'list') {
            return $this->validateList($dataType);
        }

        return false;
    }

    /**
     * @param DataType $dataType
     * @return bool
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * Each element must validate the schema
     */
    private function validateList(DataType $dataType): bool
    {
        $data = $dataType->getData();

        if ($data === null) {
            if (!$this->nullable) {
                $this->lastError = 'This list can not be `null`';
                return false;
            }
            return true;
        }

        foreach ($data as $element) {
            /** @var JsonData $jsonData */
            $jsonData = (new JsonData())->setDataFromArray($element);
            if (!$this->validateObject($jsonData)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param JsonData $dataType
     * @return bool
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    private function validateObject(JsonData $dataType): bool
    {
        $data = $dataType->getData();
        foreach ($this->rules as $property => $rule) {
            $found = false;
            foreach ($data as $key => $value) {
                if ($property === $key) {
                    $found = true;
                    if (!$rule->validate($value)) {
                        $this->lastError = '`' . $property . '` does not validate the schema. ' . $rule->getError();
                        return false;
                    }
                }
            }

            if (!$found && !$rule->isOptional()) {
                $this->lastError = '`' . $property . '` property is mandatory';
                return false;
            }
        }

        foreach ($this->children as $property => $schema) {
            $found = false;
            foreach ($data as $key => $value) {
                if ($property === $key) {
                    $found = true;

                    // $value must be null or an array
                    if (!is_array($value)) {
                        if ($value === null) {
                            if (!$schema->isNullable()) {
                                $this->lastError = '`' . $property . '` property can not be `null`';
                                return false;
                            }
                        } else if ($schema->isNullable()) {
                            $this->lastError = '`' . $property . '` property must be an array or `null`';
                            return false;
                        } else {
                            $this->lastError = '`' . $property . '` property must be an array';
                            return false;
                        }
                    }

                    if ($value === null) {
                        $childJsonData = (new JsonData())->setData(null);
                    } elseif (is_array($value)) {
                        $childJsonData = (new JsonData())->setDataFromArray($value);
                    } else {
                        $this->lastError = 'Json data must be null or an array';
                        return false;
                    }

                    if (!$schema->validate($childJsonData)) {
                        $this->lastError = 'The data passed does not validate the `' . $property . '` schema. ' . $schema->getLastError();
                        return false;
                    }
                }
            }

            if (!$found && !$schema->isOptional()) {
                $this->lastError = '`' . $property . '` property is mandatory';
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $schema
     * @return DataSchema
     * @throws InvalidSchemaException
     */
    public function setSchema(array $schema): DataSchema
    {
        foreach ($schema as $property => $rule) {
            if (!isset($rule['type'])) {
                throw new InvalidSchemaException('Each property of the schema must have a type');
            }

            $type = $rule['type'];
            $isOptional = isset($rule['optional']) ? (bool)$rule['optional'] : false;
            $canBeNull = isset($rule['null']) ? (bool)$rule['null'] : false;

            if (isset($rule['length']) && !JsonRule::isTypeWithLengthCheck($type)) {
                throw new InvalidSchemaException('`' . $type . '` type can not be length checked');
            }

            if (isset($rule['pattern']) && !JsonRule::isTypeWithPatternCheck($type)) {
                throw new InvalidSchemaException('`' . $type . '` type can not be patterned checked');
            }

            if ((isset($rule['min']) || isset($rule['max'])) && !JsonRule::isTypeWithMinMaxCheck($type)) {
                throw new InvalidSchemaException('`' . $type . '` type can not be min/max checked');
            }

            if (isset($rule['enum']) && !JsonRule::isTypeWithEnumCheck($type)) {
                throw new InvalidSchemaException('`' . $type . '` type can not be enum checked');
            }

            if (isset($rule['date-format']) && !JsonRule::isTypeWithDateFormatCheck($type)) {
                throw new InvalidSchemaException('`' . $type . '` type can not be date format checked');
            }

            $length = $rule['length'] ?? null;
            $pattern = $rule['pattern'] ?? null;
            $min = $rule['min'] ?? null;
            $max = $rule['max'] ?? null;
            $dateFormat = $rule['date-format'] ?? null;
            $enum = (isset($rule['enum']) && is_array($rule['enum'])) ? $rule['enum'] : null;

            if (in_array($type, JsonRule::LIST_TYPES, true) || in_array($type, JsonRule::OBJECT_TYPES, true)) {
                if (!isset($rule['schema'])) {
                    throw new InvalidSchemaException('`list` or `object` type must have a `schema` property to describe to list or object schema');
                }

                $s = $rule['schema'];
                if (!is_array($s)) {
                    throw new InvalidSchemaException('`schema` must be a valid schema');
                }

                /** @var JsonSchema $childSchema */
                $childSchema = (new self())->setSchema($s);
                $childSchema->setType($type)->setOptional($isOptional)->setNullable($canBeNull);
                $this->children[$property] = $childSchema;
            } else {
                $this->rules[$property] = (new JsonRule())
                    ->setType($type)
                    ->setNullable($canBeNull)
                    ->setOptional($isOptional)
                    ->setLength($length)
                    ->setPattern($pattern)
                    ->setMin($min)
                    ->setMax($max)
                    ->setEnum($enum)
                    ->setDateFormat($dateFormat);
            }
        }

        return $this;
    }
}