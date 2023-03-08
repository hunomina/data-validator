<?php

namespace hunomina\DataValidator\Test\Rule\Json;

use hunomina\DataValidator\Exception\Json\InvalidDataException;
use hunomina\DataValidator\Exception\Json\InvalidRuleException;
use hunomina\DataValidator\Rule\Json\BooleanRule;
use hunomina\DataValidator\Rule\Json\CharacterRule;
use hunomina\DataValidator\Rule\Json\FloatRule;
use hunomina\DataValidator\Rule\Json\IntegerRule;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Rule\Json\NumericRule;
use hunomina\DataValidator\Rule\Json\StringRule;
use hunomina\DataValidator\Rule\Json\TypedListRule;
use PHPUnit\Framework\TestCase;
use Throwable;

/**
 * Class TypedListRuleTest
 * @package hunomina\DataValidator\Test\Rule\Json
 * @covers \hunomina\DataValidator\Rule\Json\TypedListRule
 */
class TypedListRuleTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param JsonRule $childRule
     * @param $data
     * @param bool $success
     * @throws InvalidDataException
     */
    public function testTypedListType(JsonRule $childRule, $data, bool $success): void
    {
        $rule = new TypedListRule($childRule);
        if (!$success) {
            try {
                $rule->validate($data);
            } catch (Throwable $t) {
                // exception thrown by the typed list rule
                self::assertInstanceOf(InvalidDataException::class, $t);
                self::assertEquals(InvalidDataException::INVALID_TYPED_LIST_ELEMENT, $t->getCode());

                // exception thrown by the invalid list element (scalar type)
                $t = $t->getPrevious();
                self::assertInstanceOf(InvalidDataException::class, $t);
                self::assertEquals(InvalidDataException::INVALID_DATA_TYPE, $t->getCode());
            }
        } else {
            self::assertTrue($rule->validate($data));
        }
    }

    public function getTestableData(): array
    {
        return [
            self::ValidIntegerList(),
            self::InvalidIntegerList(),
            self::ValidStringList(),
            self::InvalidStringList(),
            self::ValidCharacterList(),
            self::InvalidCharacterList(),
            self::ValidBooleanList(),
            self::InvalidBooleanList(),
            self::ValidFloatList(),
            self::InvalidFloatList(),
            self::ValidNumericList(),
            self::InvalidNumericList()
        ];
    }

    private static function ValidIntegerList(): array
    {
        return [new IntegerRule(), [1, 2, 3, 4], true];
    }

    private static function InvalidIntegerList(): array
    {
        return [new IntegerRule(), [1, 2.89, 3.14158, 4.0], false];
    }

    private static function ValidStringList(): array
    {
        return [new StringRule(), ['I', 'am', 'testing'], true];
    }

    private static function InvalidStringList(): array
    {
        return [new StringRule(), ['I', 'am', 'testing', 2, 'times'], false];
    }

    private static function ValidCharacterList(): array
    {
        return [new CharacterRule(), ['a', 'b', 'c'], true];
    }

    private static function InvalidCharacterList(): array
    {
        return [new CharacterRule(), ['a', 'b', 3], false];
    }

    private static function ValidBooleanList(): array
    {
        return [new BooleanRule(), [true, false], true];
    }

    private static function InvalidBooleanList(): array
    {
        return [new BooleanRule(), [true, 0], false];
    }

    private static function ValidFloatList(): array
    {
        return [new FloatRule(), [1.1, 2.2, 3.3], true];
    }

    private static function InvalidFloatList(): array
    {
        return [new FloatRule(), [1.1, 2.2, 3], false];
    }

    private static function ValidNumericList(): array
    {
        return [new NumericRule(), [1.1, 2.2, 3], true];
    }

    private static function InvalidNumericList(): array
    {
        return [new NumericRule(), [1, 2.2, '3'], false];
    }

    public function testThrowsOnInvalidSetMinimumParameter(): void
    {
        $rule = new TypedListRule(new IntegerRule());

        try {
            $rule->setMinimum(-1);
        } catch (Throwable $t) {
            self::assertInstanceOf(InvalidRuleException::class, $t);
            self::assertSame(InvalidRuleException::INVALID_LIST_MIN_RULE, $t->getCode());
        }
    }

    public function testThrowsOnInvalidSetMaximumParameter(): void
    {
        $rule = new TypedListRule(new IntegerRule());

        try {
            $rule->setMaximum(-1);
        } catch (Throwable $t) {
            self::assertInstanceOf(InvalidRuleException::class, $t);
            self::assertSame(InvalidRuleException::INVALID_LIST_MAX_RULE, $t->getCode());
        }
    }

    /**
     * @dataProvider provideValuesForMinimumCheck
     */
    public function testMinimumCheck(int $minimum, array $data, bool $expectedResult): void
    {
        $rule = new TypedListRule(new StringRule());
        $rule->setMinimum($minimum);

        if (!$expectedResult) {
            try {
                $rule->validate($data);
            } catch (Throwable $t) {
                // exception thrown by the invalid list element (scalar type)
                self::assertInstanceOf(InvalidDataException::class, $t);
                self::assertEquals(InvalidDataException::INVALID_MIN_VALUE, $t->getCode());
            }
        } else {
            self::assertTrue($rule->validate($data));
        }
    }

    public function provideValuesForMinimumCheck(): array
    {
        $data = ['first', 'second', 'third'];

        return [
            'lower' => [
                'minimum' => 4,
                'data' => $data,
                'expectedResult' => false,
            ],
            'exact' => [
                'minimum' => 3,
                'data' => $data,
                'expectedResult' => true,
            ],
            'greater' => [
                'minimum' => 2,
                'data' => $data,
                'expectedResult' => true,
            ],
        ];
    }

    /**
     * @dataProvider provideValuesForMaximumCheck
     */
    public function testMaximumCheck(int $maximum, array $data, bool $expectedResult): void
    {
        $rule = new TypedListRule(new StringRule());
        $rule->setMaximum($maximum);

        if (!$expectedResult) {
            try {
                $rule->validate($data);
            } catch (Throwable $t) {
                // exception thrown by the invalid list element (scalar type)
                self::assertInstanceOf(InvalidDataException::class, $t);
                self::assertEquals(InvalidDataException::INVALID_MAX_VALUE, $t->getCode());
            }
        } else {
            self::assertTrue($rule->validate($data));
        }
    }

    public function provideValuesForMaximumCheck(): array
    {
        $data = ['first', 'second', 'third'];

        return [
            'lower' => [
                'maximum' => 4,
                'data' => $data,
                'expectedResult' => true,
            ],
            'exact' => [
                'maximum' => 3,
                'data' => $data,
                'expectedResult' => true,
            ],
            'greater' => [
                'maximum' => 2,
                'data' => $data,
                'expectedResult' => false,
            ],
        ];
    }

    /**
     * @throws InvalidDataException
     */
    public function testInvalidDataList(): void
    {
        $this->expectException(InvalidDataException::class);
        $this->expectExceptionCode(InvalidDataException::INVALID_DATA_TYPE);

        $rule = new TypedListRule(new StringRule());
        $rule->validate('not an array');
    }

    /**
     * @param JsonRule $childRule
     * @dataProvider getScalarRules
     */
    public function testGetType(JsonRule $childRule): void
    {
        $rule = new TypedListRule($childRule);
        self::assertSame($childRule->getType() . TypedListRule::LIST_TYPE_SUFFIX, $rule->getType());
    }

    public function getScalarRules(): array
    {
        return [
            [new StringRule()],
            [new CharacterRule()],
            [new IntegerRule()],
            [new FloatRule()],
            [new NumericRule()],
            [new BooleanRule()]
        ];
    }
}
