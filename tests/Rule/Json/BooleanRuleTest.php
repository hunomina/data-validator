<?php

namespace hunomina\Validator\Json\Test\Rule\Json;

use hunomina\Validator\Json\Data\Json\JsonData;
use hunomina\Validator\Json\Exception\Json\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\Json\InvalidSchemaException;
use hunomina\Validator\Json\Rule\Json\JsonRule;
use hunomina\Validator\Json\Schema\Json\JsonSchema;
use PHPUnit\Framework\TestCase;

class BooleanRuleTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param JsonData $data
     * @param JsonSchema $schema
     * @param bool $success
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    public function testBooleanRule(JsonData $data, JsonSchema $schema, bool $success): void
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
            self::ValidBooleanData(),
            self::InvalidBooleanData()
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function ValidBooleanData(): array
    {
        return [
            new JsonData([
                'boolean' => true
            ]),
            new JsonSchema([
                'boolean' => ['type' => JsonRule::BOOLEAN_TYPE]
            ]),
            true
        ];
    }

    /**
     * @return array
     * @throws InvalidSchemaException
     * @throws InvalidDataException
     */
    private static function InvalidBooleanData(): array
    {
        return [
            new JsonData([
                'boolean' => 'not-a-boolean'
            ]),
            new JsonSchema([
                'boolean' => ['type' => JsonRule::BOOLEAN_TYPE]
            ]),
            false
        ];
    }
}