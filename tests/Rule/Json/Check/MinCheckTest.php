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
 * Class MinCheckTest
 * @package hunomina\DataValidator\Test\Rule\Json\Traits
 */
class MinCheckTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param JsonRule $rule
     * @param $data
     * @param bool $success
     * @throws InvalidDataException
     */
    public function testMinCheck(JsonRule $rule, $data, bool $success): void
    {
        if (!$success) {
            $this->expectException(InvalidDataException::class);
            $this->expectExceptionCode(InvalidDataException::INVALID_MIN_VALUE);

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
     * @return array
     * @covers \hunomina\DataValidator\Rule\Json\IntegerRule
     */
    private static function MinIntegerCheck(): array
    {
        $rule = new IntegerRule();
        $rule->setMinimum(3);
        return [$rule, 4, true];
    }

    /**
     * @return array
     * @covers \hunomina\DataValidator\Rule\Json\IntegerRule
     */
    private static function MinIntegerCheckFail(): array
    {
        $rule = new IntegerRule();
        $rule->setMinimum(3);
        return [$rule, 2, false];
    }

    /**
     * @return array
     * @covers \hunomina\DataValidator\Rule\Json\FloatRule
     */
    private static function MinFloatCheck(): array
    {
        $rule = new FloatRule();
        $rule->setMinimum(3.0);
        return [$rule, 4.0, true];
    }

    /**
     * @return array
     * @covers \hunomina\DataValidator\Rule\Json\FloatRule
     */
    private static function MinFloatCheckFail(): array
    {
        $rule = new FloatRule();
        $rule->setMinimum(3.0);
        return [$rule, 2.0, false];
    }

    /**
     * @return array
     * @covers \hunomina\DataValidator\Rule\Json\NumericRule
     */
    private static function MinNumberCheck(): array
    {
        $rule = new NumericRule();
        $rule->setMinimum(3);
        return [$rule, 4.0, true];
    }

    /**
     * @return array
     * @covers \hunomina\DataValidator\Rule\Json\NumericRule
     */
    private static function MinNumberCheckFail(): array
    {
        $rule = new NumericRule();
        $rule->setMinimum(3);
        return [$rule, 2.0, false];
    }

    /**
     * @return array
     * @throws InvalidRuleException
     * @covers \hunomina\DataValidator\Rule\Json\TypedListRule
     */
    private static function MinSizeTypedListCheck(): array
    {
        $rule = new TypedListRule(new IntegerRule());
        $rule->setMinimum(2);
        return [$rule, [1, 2, 3], true];
    }

    /**
     * @return array
     * @throws InvalidRuleException
     * @covers \hunomina\DataValidator\Rule\Json\TypedListRule
     */
    private static function MinSizeTypedListCheckFail(): array
    {
        $rule = new TypedListRule(new IntegerRule());
        $rule->setMinimum(2);
        return [$rule, [1], false];
    }

    /**
     * @covers \hunomina\DataValidator\Rule\Json\Check\MinimumCheckTrait
     */
    public function testMinOptionIntegerValueCastToFloat(): void
    {
        $min = 2;
        $rule = new IntegerRule();
        $rule->setMinimum($min);
        self::assertSame((float)$min, $rule->getMinimum());
    }

    /**
     * @throws InvalidRuleException
     * @covers \hunomina\DataValidator\Rule\Json\TypedListRule
     */
    public function testThrowOnListLengthInferiorToOne(): void
    {
        $this->expectException(InvalidRuleException::class);
        $this->expectExceptionCode(InvalidRuleException::INVALID_LIST_MIN_RULE);

        $rule = new TypedListRule(new StringRule());
        $rule->setMinimum(-1);
    }
}