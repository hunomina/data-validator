<?php

namespace hunomina\Validator\Json\Test\Rule\Json;

use hunomina\Validator\Json\Data\Json\JsonData;
use hunomina\Validator\Json\Exception\Json\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\Json\InvalidSchemaException;
use hunomina\Validator\Json\Rule\Json\JsonRule;
use hunomina\Validator\Json\Schema\Json\JsonSchema;
use PHPUnit\Framework\TestCase;

class NumericRuleTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param JsonData $data
     * @param JsonSchema $schema
     * @param bool $success
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    public function testNumericType(JsonData $data, JsonSchema $schema, bool $success): void
    {
        if (!$success) {
            $this->expectException(InvalidDataException::class);
            $this->expectExceptionCode(InvalidDataException::INVALID_DATA_TYPE);

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
            self::ValidIntegerData(),
            self::ValidFloatData(),
            self::InvalidNumericData(),
            self::InvalidNumericStringData()
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function ValidIntegerData(): array
    {
        return [
            new JsonData([
                'integer' => 1
            ]),
            new JsonSchema([
                'integer' => ['type' => JsonRule::NUMERIC_TYPE]
            ]),
            true
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function ValidFloatData(): array
    {
        return [
            new JsonData([
                'number' => 1.0
            ]),
            new JsonSchema([
                'number' => ['type' => JsonRule::NUMERIC_TYPE]
            ]),
            true
        ];
    }


    /**
     * @return array
     * @throws InvalidSchemaException
     * @throws InvalidDataException
     */
    private static function InvalidNumericData(): array
    {
        return [
            new JsonData([
                'number' => false
            ]),
            new JsonSchema([
                'number' => ['type' => JsonRule::NUMERIC_TYPE]
            ]),
            false
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function InvalidNumericStringData(): array
    {
        return [
            new JsonData([
                'number' => '1'
            ]),
            new JsonSchema([
                'number' => ['type' => JsonRule::NUMERIC_TYPE]
            ]),
            false
        ];
    }
}