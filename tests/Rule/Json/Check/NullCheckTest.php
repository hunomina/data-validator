<?php

namespace hunomina\DataValidator\Test\Rule\Json\Check;

use hunomina\DataValidator\Exception\Json\InvalidDataException;
use hunomina\DataValidator\Rule\Json\CharacterRule;
use hunomina\DataValidator\Rule\Json\FloatRule;
use hunomina\DataValidator\Rule\Json\IntegerRule;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Rule\Json\NumericRule;
use hunomina\DataValidator\Rule\Json\StringRule;
use hunomina\DataValidator\Rule\Json\TypedListRule;
use PHPUnit\Framework\TestCase;

/**
 * Class NullCheckTest
 * @package hunomina\DataValidator\Test\Rule\Json\Traits
 */
class NullCheckTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param JsonRule $rule
     * @param $data
     * @param bool $success
     * @throws InvalidDataException
     */
    public function testNullCheck(JsonRule $rule, $data, bool $success): void
    {
        if (!$success) {
            $this->expectException(InvalidDataException::class);
            $this->expectExceptionCode(InvalidDataException::NULL_VALUE_NOT_ALLOWED);

            $rule->validate($data);
        } else {
            self::assertTrue($rule->validate($data));
        }
    }

    /**
     * @return array[]
     */
    public function getTestableData(): array
    {
        return [
            self::NullStringCheck(),
            self::NullStringCheckFail(),
            self::NullCharCheck(),
            self::NullCharCheckFail(),
            self::NullIntegerCheck(),
            self::NullIntegerCheckFail(),
            self::NullFloatCheck(),
            self::NullFloatCheckFail(),
            self::NullNumericCheck(),
            self::NullNumericCheckFail(),
            self::NullListCheck(),
            self::NullListCheckFail()
        ];
    }

    /**
     * @return array
     * @covers \hunomina\DataValidator\Rule\Json\StringRule
     */
    private static function NullStringCheck(): array
    {
        $rule = new StringRule();
        $rule->setNullable(true);
        return [$rule, null, true];
    }

    /**
     * @return array
     * @covers \hunomina\DataValidator\Rule\Json\StringRule
     */
    private static function NullStringCheckFail(): array
    {
        return [new StringRule(), null, false];
    }

    /**
     * @return array
     * @covers \hunomina\DataValidator\Rule\Json\CharacterRule
     */
    private static function NullCharCheck(): array
    {
        $rule = new CharacterRule();
        $rule->setNullable(true);
        return [$rule, null, true];
    }

    /**
     * @return array
     * @covers \hunomina\DataValidator\Rule\Json\CharacterRule
     */
    private static function NullCharCheckFail(): array
    {
        return [new CharacterRule(), null, false];
    }

    /**
     * @return array
     * @covers \hunomina\DataValidator\Rule\Json\IntegerRule
     */
    private static function NullIntegerCheck(): array
    {
        $rule = new IntegerRule();
        $rule->setNullable(true);
        return [$rule, null, true];
    }

    /**
     * @return array
     * @covers \hunomina\DataValidator\Rule\Json\IntegerRule
     */
    private static function NullIntegerCheckFail(): array
    {
        return [new IntegerRule(), null, false];
    }

    /**
     * @return array
     * @covers \hunomina\DataValidator\Rule\Json\FloatRule
     */
    private static function NullFloatCheck(): array
    {
        $rule = new FloatRule();
        $rule->setNullable(true);
        return [$rule, null, true];
    }

    /**
     * @return array
     * @covers \hunomina\DataValidator\Rule\Json\FloatRule
     */
    private static function NullFloatCheckFail(): array
    {
        return [new FloatRule(), null, false];
    }

    /**
     * @return array
     * @covers \hunomina\DataValidator\Rule\Json\NumericRule
     */
    private static function NullNumericCheck(): array
    {
        $rule = new NumericRule();
        $rule->setNullable(true);
        return [$rule, null, true];
    }

    /**
     * @return array
     * @covers \hunomina\DataValidator\Rule\Json\NumericRule
     */
    private static function NullNumericCheckFail(): array
    {
        return [new NumericRule(), null, false];
    }

    /**
     * @return array
     * @covers \hunomina\DataValidator\Rule\Json\TypedListRule
     */
    private static function NullListCheck(): array
    {
        $rule = new TypedListRule(new StringRule());
        $rule->setNullable(true);
        return [$rule, null, true];
    }

    /**
     * @return array
     * @covers \hunomina\DataValidator\Rule\Json\TypedListRule
     */
    private static function NullListCheckFail(): array
    {
        $rule = new TypedListRule(new StringRule());
        $rule->setNullable(false);
        return [$rule, null, false];
    }
}