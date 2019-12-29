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
    public const OBJECT_TYPE = 'object';

    public const LIST_TYPE = 'list';

    /** @var string $type */
    private string $type = self::OBJECT_TYPE;

    /** @var JsonRule[] $rule */
    private array $rules = [];

    /** @var JsonSchema[] $children */
    private array $children = [];

    /** @var bool $optional */
    private bool $optional = false;

    /** @var bool $nullable */
    private bool $nullable = false;

    /**
     * JsonSchema constructor.
     * @param array $schema
     * @throws InvalidSchemaException
     */
    public function __construct(array $schema = [])
    {
        $this->setSchema($schema);
    }

    /**
     * Reset properties to avoid keeping previous rules or children
     */
    private function reset(): void
    {
        $this->type = self::OBJECT_TYPE;
        $this->rules = [];
        $this->children = [];
        $this->optional = false;
        $this->nullable = false;
    }

    /**
     * @return JsonRule[]
     * @codeCoverageIgnore
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    /**
     * @return JsonSchema[]
     * @codeCoverageIgnore
     */
    public function getChildren(): array
    {
        return $this->children;
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
     * @param bool $optional
     * @return JsonSchema
     * @codeCoverageIgnore
     */
    public function setOptional(bool $optional): JsonSchema
    {
        $this->optional = $optional;
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
     * @param bool $canBeNull
     * @return JsonSchema
     * @codeCoverageIgnore
     */
    public function setNullable(bool $canBeNull): JsonSchema
    {
        $this->nullable = $canBeNull;
        return $this;
    }

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
     * @return JsonSchema
     * @throws InvalidSchemaException
     */
    public function setType(string $type): JsonSchema
    {
        if ($type !== self::OBJECT_TYPE && $type !== self::LIST_TYPE) {
            throw new InvalidSchemaException('Invalid schema type', InvalidSchemaException::INVALID_SCHEMA_TYPE);
        }

        $this->type = $type;
        return $this;
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
            throw new InvalidDataTypeException('JsonSchema only check JsonData', InvalidDataTypeException::INVALID_DATA_TYPE_USED);
        }

        if ($dataType->getData() === null) {
            if (!$this->nullable) {
                throw new InvalidDataException('Can not be null', InvalidDataException::NULL_VALUE_NOT_ALLOWED);
            }
            return true;
        }

        // From here $dataType->getData() is an array
        if ($this->type === self::OBJECT_TYPE) {
            return $this->validateObject($dataType);
        }

        // if it's not an object type schema, it's a list type
        return $this->validateList($dataType);
    }

    /**
     * @param JsonData $dataType
     * @return bool
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * Each element must validate the schema
     */
    private function validateList(JsonData $dataType): bool
    {
        $data = $dataType->getData();
        foreach ($data as $key => $element) {

            if (!is_array($element)) {
                throw new InvalidDataException('Element at index ' . $key . ' must be an object (array)', InvalidDataException::INVALID_LIST_ELEMENT);
            }

            $jsonData = new JsonData($element);

            try {
                $this->validateObject($jsonData);
            } catch (InvalidDataException $e) {
                throw new InvalidDataException('Element at index ' . $key . ' is invalid : ' . $e->getMessage(), InvalidDataException::INVALID_LIST_ELEMENT, $e);
            }
        }
        return true;
    }

    /**
     * @param JsonData $dataType
     * @return bool
     * @throws InvalidDataTypeException
     * @throws InvalidDataException
     */
    private function validateObject(JsonData $dataType): bool
    {
        $data = $dataType->getData();
        foreach ($this->rules as $field => $rule) {
            $found = false;
            foreach ($data as $key => $value) {
                if ($field === $key) {
                    $found = true;
                    try {
                        $rule->validate($value);
                    } catch (InvalidDataException $e) {
                        throw new InvalidDataException('`' . $field . '` does not validate the schema. ' . $e->getMessage(), $e->getCode(), $e);
                    }
                }
            }

            if (!$found && !$rule->isOptional()) {
                throw new InvalidDataException('`' . $field . '` field is mandatory', InvalidDataException::MANDATORY_FIELD);
            }
        }

        foreach ($this->children as $field => $schema) {
            $found = false;
            foreach ($data as $key => $value) {
                if ($field === $key) {
                    $found = true;

                    // $value must be null or an array because it's a child object
                    // if $value is null we check if it is allowed
                    if (!is_array($value)) {
                        if ($value === null) {
                            if (!$schema->canBeNull()) {
                                throw new InvalidDataException('`' . $field . '` field can not be `null`', InvalidDataException::INVALID_CHILD_OBJECT);
                            }
                        } else if ($schema->canBeNull()) {
                            throw new InvalidDataException('`' . $field . '` field must be an array or `null`', InvalidDataException::INVALID_CHILD_OBJECT);
                        } else {
                            throw new InvalidDataException('`' . $field . '` field must be an array', InvalidDataException::INVALID_CHILD_OBJECT);
                        }
                    }

                    // $value is null or an array
                    $childJsonData = new JsonData($value);

                    try {
                        $schema->validate($childJsonData);
                    } catch (InvalidDataException $e) {
                        throw new InvalidDataException('`' . $field . '` does not validate the schema. ' . $e->getMessage(), $e->getCode(), $e);
                    }
                }
            }

            if (!$found && !$schema->isOptional()) {
                throw new InvalidDataException('`' . $field . '` field is mandatory', InvalidDataException::MANDATORY_FIELD);
            }
        }

        return true;
    }

    /**
     * @param array $schema
     * @return JsonSchema
     * @throws InvalidSchemaException
     */
    public function setSchema(array $schema): JsonSchema
    {
        $this->reset();

        foreach ($schema as $field => $rule) {
            if (!isset($rule['type'])) {
                throw new InvalidSchemaException('Each field of the schema must have a type', InvalidSchemaException::MISSING_TYPE);
            }

            $type = $rule['type'];
            $isOptional = isset($rule['optional']) ? (bool)$rule['optional'] : false;
            $canBeNull = isset($rule['null']) ? (bool)$rule['null'] : false;

            if (isset($rule['length']) && !JsonRule::isTypeWithLengthCheck($type)) {
                throw new InvalidSchemaException('`' . $type . '` type can not be length checked', InvalidSchemaException::INVALID_LENGTH_RULE);
            }

            if (isset($rule['pattern']) && !JsonRule::isTypeWithPatternCheck($type)) {
                throw new InvalidSchemaException('`' . $type . '` type can not be patterned checked', InvalidSchemaException::INVALID_PATTERN_RULE);
            }

            if (isset($rule['min']) && !JsonRule::isTypeWithMinMaxCheck($type)) {
                throw new InvalidSchemaException('`' . $type . '` type can not be min checked', InvalidSchemaException::INVALID_MIN_RULE);
            }

            if (isset($rule['max']) && !JsonRule::isTypeWithMinMaxCheck($type)) {
                throw new InvalidSchemaException('`' . $type . '` type can not be max checked', InvalidSchemaException::INVALID_MAX_RULE);
            }

            if (isset($rule['enum']) && !JsonRule::isTypeWithEnumCheck($type)) {
                throw new InvalidSchemaException('`' . $type . '` type can not be enum checked', InvalidSchemaException::INVALID_ENUM_RULE);
            }

            if (isset($rule['date-format']) && !JsonRule::isTypeWithDateFormatCheck($type)) {
                throw new InvalidSchemaException('`' . $type . '` type can not be date format checked', InvalidSchemaException::INVALID_DATE_FORMAT_RULE);
            }

            if (isset($rule['empty']) && !JsonRule::isTypeWithEmptyCheck($type)) {
                throw new InvalidSchemaException('`' . $type . '` type can not be empty checked', InvalidSchemaException::INVALID_EMPTY_RULE);
            }

            $length = $rule['length'] ?? null;
            $pattern = $rule['pattern'] ?? null;
            $min = $rule['min'] ?? null;
            $max = $rule['max'] ?? null;
            $dateFormat = $rule['date-format'] ?? null;
            $enum = isset($rule['enum']) && is_array($rule['enum']) ? $rule['enum'] : null;
            $canBeEmpty = $rule['empty'] ?? true;

            if ($type === JsonRule::LIST_TYPE || $type === JsonRule::OBJECT_TYPE) {
                if (!isset($rule['schema'])) {
                    throw new InvalidSchemaException('`list` or `object` type must have a `schema` property', InvalidSchemaException::MISSING_SCHEMA);
                }

                $s = $rule['schema'];
                if (!is_array($s)) {
                    throw new InvalidSchemaException('`schema` must be a valid schema', InvalidSchemaException::INVALID_OBJECT_SCHEMA);
                }

                $childSchema = new self($s);
                $childSchema->setType($type)->setOptional($isOptional)->setNullable($canBeNull);
                $this->children[$field] = $childSchema;
            } else {
                $this->rules[$field] = (new JsonRule())
                    ->setType($type)
                    ->setNullable($canBeNull)
                    ->setOptional($isOptional)
                    ->setLength($length)
                    ->setPattern($pattern)
                    ->setMin($min)
                    ->setMax($max)
                    ->setEnum($enum)
                    ->setDateFormat($dateFormat)
                    ->setEmpty($canBeEmpty);
            }
        }

        return $this;
    }
}