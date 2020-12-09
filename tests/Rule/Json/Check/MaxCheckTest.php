<?php

namespace hunomina\DataValidator\Test\Rule\Json\Check;

use hunomina\DataValidator\Exception\Json\InvalidDataException;
use hunomina\DataValidator\Exception\Json\InvalidRuleException;
use hunomina\DataValidator\Rule\Json\FloatRule;
use hunomina\DataValidator\Rule\Json\IntegerRule;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Rule\Json\NumericRule;
use hunomina\DataValidator\Rule\Json\StringRule;
use hunomina\DataValidator\Rule\Json\TypedListRule;
use PHPUnit\Framework\TestCase;

/**
 * Class MaxCheckTest
 * @package hunomina\DataValidator\Test\Rule\Json\Traits
 */
class MaxCheckTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param JsonRule $rule
     * @param $data
     * @param bool $success
     * @throws InvalidDataException
     */
    public function testMaxCheck(JsonRule $rule, $data, bool $success): void
    {
        if (!$success) {
            $this->expectException(InvalidDataException::class);
            $this->expectExceptionCode(InvalidDataException::INVALID_MAX_VALUE);

            $rule->validate($data);
        } else {
            self::assertTrue($rule->validate($data));
        }
    }

    /**
     * @return array[]
     * @throws InvalidRuleException
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
     * @return array
     * @covers \hunomina\DataValidator\Rule\Json\IntegerRule
     */
    private static function MaxIntegerCheck(): array
    {
        $rule = new IntegerRule();
        $rule->setMaximum(3);
        return [$rule, 2, true];
    }

    /**
     * @return array
     * @covers \hunomina\DataValidator\Rule\Json\IntegerRule
     */
    private static function MaxIntegerCheckFail(): array
    {
        $rule = new IntegerRule();
        $rule->setMaximum(3);
        return [$rule, 4, false];
    }

    /**
     * @return array
     * @covers \hunomina\DataValidator\Rule\Json\FloatRule
     */
    private static function MaxFloatCheck(): array
    {
        $rule = new FloatRule();
        $rule->setMaximum(3.0);
        return [$rule, 2.0, true];
    }

    /**
     * @return array
     * @covers \hunomina\DataValidator\Rule\Json\FloatRule
     */
    private static function MaxFloatCheckFail(): array
    {
        $rule = new FloatRule();
        $rule->setMaximum(3.0);
        return [$rule, 4.0, false];
    }

    /**
     * @return array
     * @covers \hunomina\DataValidator\Rule\Json\NumericRule
     */
    private static function MaxNumberCheck(): array
    {
        $rule = new NumericRule();
        $rule->setMaximum(3);
        return [$rule, 2.0, true];
    }

    /**
     * @return array
     * @covers \hunomina\DataValidator\Rule\Json\NumericRule
     */
    private static function MaxNumberCheckFail(): array
    {
        $rule = new NumericRule();
        $rule->setMaximum(3);
        return [$rule, 4.0, false];
    }

    /**
     * @return array
     * @throws InvalidRuleException
     * @covers \hunomina\DataValidator\Rule\Json\TypedListRule
     */
    private static function MaxSizeTypedListCheck(): array
    {
        $rule = new TypedListRule(new IntegerRule());
        $rule->setMaximum(2);
        return [$rule, [1], true];
    }

    /**
     * @return array
     * @covers \hunomina\DataValidator\Rule\Json\TypedListRule
     * @throws InvalidRuleException
     */
    private static function MaxSizeTypedListCheckFail(): array
    {
        $rule = new TypedListRule(new IntegerRule());
        $rule->setMaximum(2);
        return [$rule, [1, 2, 3], false];
    }

    /**
     * @covers \hunomina\DataValidator\Rule\Json\Check\MaximumCheckTrait
     */
    public function testMaxOptionIntegerValueCastToFloat(): void
    {
        $max = 2;
        $rule = new IntegerRule();
        $rule->setMaximum($max);
        self::assertSame((float)$max, $rule->getMaximum());
    }

    /**
     * @throws InvalidRuleException
     * @covers \hunomina\DataValidator\Rule\Json\TypedListRule
     */
    public function testThrowOnListLengthInferiorToOne(): void
    {
        $this->expectException(InvalidRuleException::class);
        $this->expectExceptionCode(InvalidRuleException::INVALID_LIST_MAX_RULE);

        $rule = new TypedListRule(new StringRule());
        $rule->setMaximum(-1);
    }
}