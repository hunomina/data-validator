<?php

namespace hunomina\DataValidator\Test\Rule\Json\Check;

use hunomina\DataValidator\Data\Json\JsonData;
use hunomina\DataValidator\Exception\Json\InvalidDataException;
use hunomina\DataValidator\Rule\Json\IntegerRule;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use PHPUnit\Framework\TestCase;

/**
 * Class MinCheckTest
 * @package hunomina\DataValidator\Test\Rule\Json\Traits
 * @covers \hunomina\DataValidator\Rule\Json\Check\MinimumCheckTrait
 */
class MinCheckTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param JsonData $data
     * @param JsonSchema $schema
     * @param bool $success
     * @throws InvalidDataException
     */
    public function testMinCheck(JsonData $data, JsonSchema $schema, bool $success): void
    {
        if (!$success) {
            $this->expectException(InvalidDataException::class);
            $this->expectExceptionCode(InvalidDataException::INVALID_MIN_VALUE);

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
            self::MinIntegerCheck(),
            self::MinIntegerCheckFail(),
            self::MinFloatCheck(),
            self::MinFloatCheckFail(),
            self::MinNumberCheck(),
            self::MinNumberCheckFail(),
            self::MinSizeTypedListCheck(),
            self::MinSizeTypedListCheckFail()
        ];
    }

    /**
     * @throws InvalidDataException
     */
    private static function MinIntegerCheck(): array
    {
        return [
            new JsonData([
                'integer' => 4
            ]),
            new JsonSchema([
                'integer' => ['type' => JsonRule::INTEGER_TYPE, 'min' => 3]
            ]),
            true
        ];
    }

    /**
     * @throws InvalidDataException
     */
    private static function MinIntegerCheckFail(): array
    {
        return [
            new JsonData([
                'integer' => 2
            ]),
            new JsonSchema([
                'integer' => ['type' => JsonRule::INTEGER_TYPE, 'min' => 3]
            ]),
            false
        ];
    }

    /**
     * @throws InvalidDataException
     */
    private static function MinFloatCheck(): array
    {
        return [
            new JsonData([
                'float' => 4.0
            ]),
            new JsonSchema([
                'float' => ['type' => JsonRule::FLOAT_TYPE, 'min' => 3.0]
            ]),
            true
        ];
    }

    /**
     * @throws InvalidDataException
     */
    private static function MinFloatCheckFail(): array
    {
        return [
            new JsonData([
                'float' => 2.0
            ]),
            new JsonSchema([
                'float' => ['type' => JsonRule::FLOAT_TYPE, 'min' => 3.0]
            ]),
            false
        ];
    }

    /**
     * @throws InvalidDataException
     */
    private static function MinNumberCheck(): array
    {
        return [
            new JsonData([
                'number' => 4.0
            ]),
            new JsonSchema([
                'number' => ['type' => JsonRule::NUMERIC_TYPE, 'min' => 3]
            ]),
            true
        ];
    }

    /**
     * @throws InvalidDataException
     */
    private static function MinNumberCheckFail(): array
    {
        return [
            new JsonData([
                'number' => 2.0
            ]),
            new JsonSchema([
                'number' => ['type' => JsonRule::NUMERIC_TYPE, 'min' => 3]
            ]),
            false
        ];
    }

    /**
     * @throws InvalidDataException
     */
    private static function MinSizeTypedListCheck(): array
    {
        return [
            new JsonData([
                'list' => [1, 2, 3]
            ]),
            new JsonSchema([
                'list' => ['type' => JsonRule::INTEGER_LIST_TYPE, 'list-min' => 2]
            ]),
            true
        ];
    }

    /**
     * @throws InvalidDataException
     */
    private static function MinSizeTypedListCheckFail(): array
    {
        return [
            new JsonData([
                'list' => [1]
            ]),
            new JsonSchema([
                'list' => ['type' => JsonRule::INTEGER_LIST_TYPE, 'list-min' => 2]
            ]),
            false
        ];
    }

    public function testMinOptionIntegerValueCastToFloat(): void
    {
        $min = 2;
        $rule = new IntegerRule();
        $rule->setMinimum($min);
        self::assertSame((float)$min, $rule->getMinimum());
    }
}