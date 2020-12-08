<?php

namespace hunomina\DataValidator\Test\Rule\Json;

use hunomina\DataValidator\Data\Json\JsonData;
use hunomina\DataValidator\Exception\Json\InvalidDataException;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use PHPUnit\Framework\TestCase;

/**
 * Class NumericRuleTest
 * @package hunomina\DataValidator\Test\Rule\Json
 * @covers \hunomina\DataValidator\Rule\Json\NumericRule
 */
class NumericRuleTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param JsonData $data
     * @param JsonSchema $schema
     * @param bool $success
     * @throws InvalidDataException
     */
    public function testNumericType(JsonData $data, JsonSchema $schema, bool $success): void
    {
        if (!$success) {
            $this->expectException(InvalidDataException::class);
            $this->expectExceptionCode(InvalidDataException::INVALID_DATA_TYPE);

            $schema->validate($data);
        } else {
            self::assertTrue($schema->validate($data));
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
            self::ValidFloatData(),
            self::InvalidNumericData(),
            self::InvalidNumericStringData()
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
                'integer' => ['type' => JsonRule::NUMERIC_TYPE]
            ]),
            true
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
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