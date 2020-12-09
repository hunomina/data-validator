<?php

namespace hunomina\DataValidator\Test\Rule\Json\Check;

use hunomina\DataValidator\Data\Json\JsonData;
use hunomina\DataValidator\Exception\Json\InvalidDataException;
use hunomina\DataValidator\Rule\Json\IntegerRule;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use PHPUnit\Framework\TestCase;

/**
 * Class MaxCheckTest
 * @package hunomina\DataValidator\Test\Rule\Json\Traits
 * @covers \hunomina\DataValidator\Rule\Json\Check\MaximumCheckTrait
 */
class MaxCheckTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param JsonData $data
     * @param JsonSchema $schema
     * @param bool $success
     * @throws InvalidDataException
     */
    public function testMaxCheck(JsonData $data, JsonSchema $schema, bool $success): void
    {
        if (!$success) {
            $this->expectException(InvalidDataException::class);
            $this->expectExceptionCode(InvalidDataException::INVALID_MAX_VALUE);

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
     */
    private static function MaxSizeTypedListCheck(): array
    {
        return [
            new JsonData([
                'list' => [1]
            ]),
            new JsonSchema([
                'list' => ['type' => JsonRule::INTEGER_LIST_TYPE, 'list-max' => 2]
            ]),
            true
        ];
    }

    /**
     * @throws InvalidDataException
     */
    private static function MaxSizeTypedListCheckFail(): array
    {
        return [
            new JsonData([
                'list' => [1, 2, 3]
            ]),
            new JsonSchema([
                'list' => ['type' => JsonRule::INTEGER_LIST_TYPE, 'list-max' => 2]
            ]),
            false
        ];
    }

    public function testMaxOptionIntegerValueCastToFloat(): void
    {
        $max = 2;
        $rule = new IntegerRule();
        $rule->setMaximum($max);
        self::assertSame((float)$max, $rule->getMaximum());
    }
}