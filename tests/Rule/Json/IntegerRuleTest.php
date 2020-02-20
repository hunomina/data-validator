<?php

namespace hunomina\DataValidator\Test\Rule\Json;

use hunomina\DataValidator\Data\Json\JsonData;
use hunomina\DataValidator\Exception\Json\InvalidDataException;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use PHPUnit\Framework\TestCase;

class IntegerRuleTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param JsonData $data
     * @param JsonSchema $schema
     * @param bool $success
     * @throws InvalidDataException
     */
    public function testIntegerType(JsonData $data, JsonSchema $schema, bool $success): void
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
     */
    public function getTestableData(): array
    {
        return [
            self::ValidIntegerData(),
            self::InvalidIntegerData()
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     */
    private static function ValidIntegerData(): array
    {
        return [
            new JsonData([
                'integer' => 1
            ]),
            new JsonSchema([
                'integer' => ['type' => JsonRule::INTEGER_TYPE]
            ]),
            true
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     */
    private static function InvalidIntegerData(): array
    {
        return [
            new JsonData([
                'integer' => false
            ]),
            new JsonSchema([
                'integer' => ['type' => JsonRule::INTEGER_TYPE]
            ]),
            false
        ];
    }
}