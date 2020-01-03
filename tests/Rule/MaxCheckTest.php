<?php

namespace hunomina\Validator\Json\Test\Rule;

use hunomina\Validator\Json\Data\Json\JsonData;
use hunomina\Validator\Json\Exception\Json\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\Json\InvalidSchemaException;
use hunomina\Validator\Json\Rule\Json\JsonRule;
use hunomina\Validator\Json\Schema\Json\JsonSchema;
use PHPUnit\Framework\TestCase;

class MaxCheckTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param JsonData $data
     * @param JsonSchema $schema
     * @param bool $success
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    public function testMaxCheck(JsonData $data, JsonSchema $schema, bool $success): void
    {
        if (!$success) {
            $this->expectException(InvalidDataException::class);
            $this->expectExceptionCode(InvalidDataException::INVALID_MAX_VALUE);

            $schema->validate($data);
        } else {
            $this->assertTrue($schema->validate($data));
        }
    }

    /**
     * @return array
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    public function getTestableData(): array
    {
        return [
            self::MaxIntegerCheck(),
            self::MaxIntegerCheckFail(),
            self::MaxFloatCheck(),
            self::MaxFloatCheckFail(),
            self::MaxNumberCheck(),
            self::MaxNumberCheckFail(),
            self::MaxSizeTypedListCheck(),
            self::MaxSizeTypedListCheckFail()
        ];
    }

    /**
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function MaxIntegerCheck(): array
    {
        return [
            new JsonData([
                'integer' => 2
            ]),
            new JsonSchema([
                'integer' => ['type' => JsonRule::INTEGER_TYPE, 'max' => 3]
            ]),
            true
        ];
    }

    /**
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function MaxIntegerCheckFail(): array
    {
        return [
            new JsonData([
                'integer' => 4
            ]),
            new JsonSchema([
                'integer' => ['type' => JsonRule::INTEGER_TYPE, 'max' => 3]
            ]),
            false
        ];
    }

    /**
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function MaxFloatCheck(): array
    {
        return [
            new JsonData([
                'float' => 2.0
            ]),
            new JsonSchema([
                'float' => ['type' => JsonRule::FLOAT_TYPE, 'max' => 3.0]
            ]),
            true
        ];
    }

    /**
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function MaxFloatCheckFail(): array
    {
        return [
            new JsonData([
                'float' => 4.0
            ]),
            new JsonSchema([
                'float' => ['type' => JsonRule::FLOAT_TYPE, 'max' => 3.0]
            ]),
            false
        ];
    }

    /**
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function MaxNumberCheck(): array
    {
        return [
            new JsonData([
                'number' => 2.0
            ]),
            new JsonSchema([
                'number' => ['type' => JsonRule::NUMERIC_TYPE, 'max' => 3]
            ]),
            true
        ];
    }

    /**
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function MaxNumberCheckFail(): array
    {
        return [
            new JsonData([
                'number' => 4.0
            ]),
            new JsonSchema([
                'number' => ['type' => JsonRule::NUMERIC_TYPE, 'max' => 3]
            ]),
            false
        ];
    }

    /**
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function MaxSizeTypedListCheck(): array
    {
        return [
            new JsonData([
                'list' => [1]
            ]),
            new JsonSchema([
                'list' => ['type' => JsonRule::INTEGER_LIST_TYPE, 'max' => 2]
            ]),
            true
        ];
    }

    /**
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function MaxSizeTypedListCheckFail(): array
    {
        return [
            new JsonData([
                'list' => [1, 2, 3]
            ]),
            new JsonSchema([
                'list' => ['type' => JsonRule::INTEGER_LIST_TYPE, 'max' => 2]
            ]),
            false
        ];
    }
}