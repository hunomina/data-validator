<?php

namespace hunomina\DataValidator\Test\Rule\Json\Check;

use hunomina\DataValidator\Exception\Json\InvalidDataException;
use hunomina\DataValidator\Exception\Json\InvalidRuleException;
use hunomina\DataValidator\Rule\Json\CharacterRule;
use hunomina\DataValidator\Rule\Json\FloatRule;
use hunomina\DataValidator\Rule\Json\IntegerRule;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Rule\Json\NumericRule;
use hunomina\DataValidator\Rule\Json\StringRule;
use PHPUnit\Framework\TestCase;

/**
 * Class EnumCheckTest
 * @package hunomina\DataValidator\Test\Rule\Json\Traits
 */
class EnumCheckTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param JsonRule $rule
     * @param $data
     * @param bool $success
     * @throws InvalidDataException
     */
    public function testEnumCheck(JsonRule $rule, $data, bool $success): void
    {
        if (!$success) {
            $this->expectException(InvalidDataException::class);
            $this->expectExceptionCode(InvalidDataException::UNAUTHORIZED_VALUE);

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
            self::EnumForString(),
            self::EnumForStringFail(),
            self::EnumForInteger(),
            self::EnumForIntegerFail(),
            self::EnumForFloat(),
            self::EnumForFloatFail(),
            self::EnumForNumber(),
            self::EnumForNumberFail(),
            self::EnumForCharacter(),
            self::EnumForCharacterFail()
        ];
    }

    /**
     * @return array
     * @throws InvalidRuleException
     * @covers \hunomina\DataValidator\Rule\Json\StringRule
     */
    private static function EnumForString(): array
    {
        $rule = new StringRule();
        $rule->setEnum(['male', 'female']);
        return [$rule, 'female', true];
    }

    /**
     * @return array
     * @throws InvalidRuleException
     * @covers \hunomina\DataValidator\Rule\Json\StringRule
     */
    private static function EnumForStringFail(): array
    {
        $rule = new StringRule();
        $rule->setEnum(['male', 'female']);
        return [$rule, 'fish', false];
    }

    /**
     * @return array
     * @throws InvalidRuleException
     * @covers \hunomina\DataValidator\Rule\Json\IntegerRule
     */
    private static function EnumForInteger(): array
    {
        $rule = new IntegerRule();
        $rule->setEnum([2, 3, 4]);
        return [$rule, 2, true];
    }

    /**
     * @return array
     * @throws InvalidRuleException
     * @covers \hunomina\DataValidator\Rule\Json\IntegerRule
     */
    private static function EnumForIntegerFail(): array
    {
        $rule = new IntegerRule();
        $rule->setEnum([2, 3, 4]);
        return [$rule, 1, false];
    }

    /**
     * @return array
     * @throws InvalidRuleException
     * @covers \hunomina\DataValidator\Rule\Json\FloatRule
     */
    private static function EnumForFloat(): array
    {
        $rule = new FloatRule();
        $rule->setEnum([2.0, 3.0, 4.0]);
        return [$rule, 2.0, true];
    }

    /**
     * @return array
     * @throws InvalidRuleException
     * @covers \hunomina\DataValidator\Rule\Json\FloatRule
     */
    private static function EnumForFloatFail(): array
    {
        $rule = new FloatRule();
        $rule->setEnum([2.0, 3.0, 4.0]);
        return [$rule, 1.0, false];
    }

    /**
     * @return array
     * @throws InvalidRuleException
     * @covers \hunomina\DataValidator\Rule\Json\NumericRule
     */
    private static function EnumForNumber(): array
    {
        $rule = new NumericRule();
        $rule->setEnum([2.0, 3, 4.0]);
        return [$rule, 2.0, true];
    }

    /**
     * @return array
     * @throws InvalidRuleException
     * @covers \hunomina\DataValidator\Rule\Json\NumericRule
     */
    private static function EnumForNumberFail(): array
    {
        $rule = new NumericRule();
        $rule->setEnum([2.0, 3, 4.0]);
        return [$rule, 1, false];
    }

    /**
     * @return array
     * @throws InvalidRuleException
     * @covers \hunomina\DataValidator\Rule\Json\CharacterRule
     */
    private static function EnumForCharacter(): array
    {
        $rule = new CharacterRule();
        $rule->setEnum(['A', 'B', 'O']);
        return [$rule, 'A', true];
    }

    /**
     * @return array
     * @throws InvalidRuleException
     * @covers \hunomina\DataValidator\Rule\Json\CharacterRule
     */
    private static function EnumForCharacterFail(): array
    {
        $rule = new CharacterRule();
        $rule->setEnum(['A', 'B', 'O']);
        return [$rule, 'C', false];
    }

    public function testThrowOnEmptyEnum(): void
    {
        $this->expectException(InvalidRuleException::class);
        $this->expectExceptionCode(InvalidRuleException::INVALID_ENUM_RULE);

        $rule = new StringRule();
        $rule->setEnum([]);
    }

    /**
     * @throws InvalidRuleException
     */
    public function testRuleCreation(): void
    {
        $enum = [1, 2, 3];

        $rule = new StringRule();
        $rule->setEnum($enum);

        self::assertSame($enum, $rule->getEnum());
    }
}