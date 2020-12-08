<?php

namespace hunomina\DataValidator\Test\Rule\Json;

use hunomina\DataValidator\Data\Json\JsonData;
use hunomina\DataValidator\Exception\Json\InvalidDataException;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use PHPUnit\Framework\TestCase;

/**
 * Class BooleanRuleTest
 * @package hunomina\DataValidator\Test\Rule\Json
 * @covers \hunomina\DataValidator\Rule\Json\BooleanRule
 */
class BooleanRuleTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param JsonData $data
     * @param JsonSchema $schema
     * @param bool $success
     * @throws InvalidDataException
     */
    public function testBooleanRule(JsonData $data, JsonSchema $schema, bool $success): void
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
            self::ValidBooleanData(),
            self::InvalidBooleanData()
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
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