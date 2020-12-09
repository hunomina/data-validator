<?php

namespace hunomina\DataValidator\Test\Rule\Json\Check;

use hunomina\DataValidator\Exception\Json\InvalidDataException;
use hunomina\DataValidator\Rule\Json\IntegerRule;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Rule\Json\StringRule;
use hunomina\DataValidator\Rule\Json\TypedListRule;
use PHPUnit\Framework\TestCase;

/**
 * Class EmptyCheckTest
 * @package hunomina\DataValidator\Test\Rule\Json\Traits
 */
class EmptyCheckTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param JsonRule $rule
     * @param $data
     * @param bool $success
     * @throws InvalidDataException
     */
    public function testEmptyCheck(JsonRule $rule, $data, bool $success): void
    {
        if (!$success) {
            $this->expectException(InvalidDataException::class);
            $this->expectExceptionCode(InvalidDataException::EMPTY_VALUE_NOT_ALLOWED);

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
            self::EmptyRuleOnString(),
            self::EmptyRuleOnStringFail(),
            self::EmptyRuleOnTypedList(),
            self::EmptyRuleOnTypedListFail(),
        ];
    }

    /**
     * @return array
     * @covers \hunomina\DataValidator\Rule\Json\StringRule
     */
    private static function EmptyRuleOnString(): array
    {
        $rule = new StringRule();
        $rule->setEmpty(true);
        return [$rule, '', true];
    }

    /**
     * @return array
     * @covers \hunomina\DataValidator\Rule\Json\StringRule
     */
    private static function EmptyRuleOnStringFail(): array
    {
        $rule = new StringRule();
        $rule->setEmpty(false);
        return [$rule, '', false];
    }

    /**
     * @return array
     * @covers \hunomina\DataValidator\Rule\Json\TypedListRule
     */
    private static function EmptyRuleOnTypedList(): array
    {
        $rule = new TypedListRule(new IntegerRule());
        $rule->setEmpty(true);
        return [$rule, [], true];
    }

    /**
     * @return array
     * @covers \hunomina\DataValidator\Rule\Json\TypedListRule
     */
    private static function EmptyRuleOnTypedListFail(): array
    {
        $rule = new TypedListRule(new IntegerRule());
        $rule->setEmpty(false);
        return [$rule, [], false];
    }
}