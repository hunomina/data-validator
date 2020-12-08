<?php

namespace hunomina\DataValidator\Test\Rule\Json;

use hunomina\DataValidator\Data\Json\JsonData;
use hunomina\DataValidator\Exception\Json\InvalidDataException;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use PHPUnit\Framework\TestCase;

/**
 * Class FloatRuleTest
 * @package hunomina\DataValidator\Test\Rule\Json
 * @covers \hunomina\DataValidator\Rule\Json\FloatRule
 */
class FloatRuleTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param JsonData $data
     * @param JsonSchema $schema
     * @param bool $success
     * @throws InvalidDataException
     */
    public function testFloatType(JsonData $data, JsonSchema $schema, bool $success): void
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
            self::ValidFloatData(),
            self::InvalidFloatData()
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
                'float' => 1.0
            ]),
            new JsonSchema([
                'float' => ['type' => JsonRule::FLOAT_TYPE]
            ]),
            true
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     */
    private static function InvalidFloatData(): array
    {
        return [
            new JsonData([
                'float' => 1
            ]),
            new JsonSchema([
                'float' => ['type' => JsonRule::FLOAT_TYPE]
            ]),
            false
        ];
    }
}