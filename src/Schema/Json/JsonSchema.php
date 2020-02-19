<?php

namespace hunomina\DataValidator\Schema\Json;

use hunomina\DataValidator\Data\DataType;
use hunomina\DataValidator\Data\Json\JsonData;
use hunomina\DataValidator\Exception\InvalidDataTypeArgumentException;
use hunomina\DataValidator\Exception\Json\InvalidDataException;
use hunomina\DataValidator\Exception\Json\InvalidRuleException;
use hunomina\DataValidator\Exception\Json\InvalidSchemaException;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Rule\Json\Factory\JsonRuleFactory;
use hunomina\DataValidator\Schema\DataSchema;

class JsonSchema implements DataSchema
{
    public const OBJECT_TYPE = 'object';

    public const LIST_TYPE = 'list';

    /** @var string $type */
    private string $type;

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
     * @param string $type
     */
    public function __construct(array $schema = [], string $type = self::OBJECT_TYPE)
    {
        $this->setSchema($schema);
        $this->setType($type);
    }

    /**
     * Reset properties to avoid keeping previous rules or children
     */
    private function reset(): void
    {
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
     */
    public function validate(DataType $dataType): bool
    {
        if (!($dataType instanceof JsonData)) {
            throw new InvalidDataTypeArgumentException('JsonSchema only validate JsonData');
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
     */
    public function setSchema(array $schema): JsonSchema
    {
        $this->reset();

        foreach ($schema as $rule => $options) {
            if (!isset($options['type'])) {
                throw new InvalidSchemaException('Each field of the schema must have a type', InvalidSchemaException::MISSING_TYPE);
            }

            $type = $options['type'];

            if ($type === JsonRule::LIST_TYPE || $type === JsonRule::OBJECT_TYPE) {
                if (!isset($options['schema'])) {
                    throw new InvalidSchemaException('`list` or `object` type must have a `schema` property', InvalidSchemaException::MISSING_SCHEMA);
                }

                $s = $options['schema'];
                if (!is_array($s)) {
                    throw new InvalidSchemaException('`schema` option must be an array', InvalidSchemaException::INVALID_CHILD_SCHEMA);
                }

                $isOptional = isset($options['optional']) ? (bool)$options['optional'] : false;
                $canBeNull = isset($options['null']) ? (bool)$options['null'] : false;

                try {
                    $childSchema = new self($s);
                } catch (InvalidSchemaException $e) {
                    throw new InvalidSchemaException('Invalid `' . $rule . '` child schema : ', InvalidSchemaException::INVALID_CHILD_SCHEMA);
                }

                $childSchema->setType($type)->setOptional($isOptional)->setNullable($canBeNull);
                $this->children[$rule] = $childSchema;
            } else {
                try {
                    $this->rules[$rule] = JsonRuleFactory::create($type, $options);
                } catch (InvalidRuleException $e) {
                    throw new InvalidSchemaException('Invalid `' . $rule . '` rule : ' . $e->getMessage(), InvalidSchemaException::INVALID_SCHEMA_RULE, $e);
                }
            }
        }

        return $this;
    }
}