<?php

namespace hunomina\DataValidator\Test\Rule\Json\Check;

use hunomina\DataValidator\Exception\Json\InvalidDataException;
use hunomina\DataValidator\Exception\Json\InvalidRuleException;
use hunomina\DataValidator\Rule\Json\IntegerRule;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Rule\Json\StringRule;
use hunomina\DataValidator\Rule\Json\TypedListRule;
use PHPUnit\Framework\TestCase;

/**
 * Class LengthCheckTest
 * @package hunomina\DataValidator\Test\Rule\Json\Traits
 */
class LengthCheckTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param JsonRule $rule
     * @param $data
     * @param bool $success
     * @throws InvalidDataException
     */
    public function testLengthCheck(JsonRule $rule, $data, bool $success): void
    {
        if (!$success) {
            $this->expectException(InvalidDataException::class);
            $this->expectExceptionCode(InvalidDataException::INVALID_LENGTH);

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
            self::StringLengthCheck(),
            self::StringLengthCheckFail(),
            self::TypedListLengthCheck(),
            self::TypedListLengthCheckFail()
        ];
    }

    /**
     * @return array
     * @throws InvalidRuleException
     * @covers \hunomina\DataValidator\Rule\Json\StringRule
     */
    private static function StringLengthCheck(): array
    {
        $rule = new StringRule();
        $rule->setLength(4);
        return [$rule, 'test', true];
    }

    /**
     * @return array
     * @throws InvalidRuleException
     * @covers \hunomina\DataValidator\Rule\Json\StringRule
     */
    private static function StringLengthCheckFail(): array
    {
        $rule = new StringRule();
        $rule->setLength(4);
        return [$rule, 'tests', false];
    }

    /**
     * @return array
     * @throws InvalidRuleException
     * @covers \hunomina\DataValidator\Rule\Json\TypedListRule
     */
    private static function TypedListLengthCheck(): array
    {
        $rule = new TypedListRule(new IntegerRule());
        $rule->setLength(4);
        return [$rule, [1, 2, 3, 4], true];
    }

    /**
     * @return array
     * @throws InvalidRuleException
     * @covers \hunomina\DataValidator\Rule\Json\TypedListRule
     */
    private static function TypedListLengthCheckFail(): array
    {
        $rule = new TypedListRule(new IntegerRule());
        $rule->setLength(4);
        return [$rule, [1, 2, 3, 4, 5], false];
    }

    /**
     * @throws InvalidRuleException
     * @covers \hunomina\DataValidator\Rule\Json\StringRule
     */
    public function testThrowOnScalarLengthEqualsZero(): void
    {
        $this->expectException(InvalidRuleException::class);
        $this->expectExceptionCode(InvalidRuleException::INVALID_LENGTH_RULE);

        $rule = new StringRule();
        $rule->setLength(0);
    }

    /**
     * @throws InvalidRuleException
     * @covers \hunomina\DataValidator\Rule\Json\StringRule
     */
    public function testThrowOnScalarLengthInferiorToOne(): void
    {
        $this->expectException(InvalidRuleException::class);
        $this->expectExceptionCode(InvalidRuleException::INVALID_LENGTH_RULE);

        $rule = new StringRule();
        $rule->setLength(-1);
    }

    /**
     * @throws InvalidRuleException
     * @covers \hunomina\DataValidator\Rule\Json\StringRule
     */
    public function testCreation(): void
    {
        $length = 1;

        $rule = new StringRule();
        $rule->setLength($length);

        self::assertSame($length, $rule->getLength());
    }

    /**
     * @throws InvalidRuleException
     * @covers \hunomina\DataValidator\Rule\Json\TypedListRule
     */
    public function testThrowOnListLengthEqualsZero(): void
    {
        $this->expectException(InvalidRuleException::class);
        $this->expectExceptionCode(InvalidRuleException::INVALID_LIST_LENGTH_RULE);

        $rule = new TypedListRule(new StringRule());
        $rule->setLength(0);
    }

    /**
     * @throws InvalidRuleException
     * @covers \hunomina\DataValidator\Rule\Json\TypedListRule
     */
    public function testThrowOnListLengthInferiorToOne(): void
    {
        $this->expectException(InvalidRuleException::class);
        $this->expectExceptionCode(InvalidRuleException::INVALID_LIST_LENGTH_RULE);

        $rule = new TypedListRule(new StringRule());
        $rule->setLength(-1);
    }
}