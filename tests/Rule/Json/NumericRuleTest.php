<?php

namespace hunomina\DataValidator\Test\Rule\Json;

use hunomina\DataValidator\Exception\Json\InvalidDataException;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Rule\Json\NumericRule;
use PHPUnit\Framework\TestCase;

/**
 * Class NumericRuleTest
 * @package hunomina\DataValidator\Test\Rule\Json
 * @covers \hunomina\DataValidator\Rule\Json\NumericRule
 */
class NumericRuleTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param $data
     * @param bool $success
     * @throws InvalidDataException
     */
    public function testNumericType($data, bool $success): void
    {
        $rule = new NumericRule();
        if (!$success) {
            $this->expectException(InvalidDataException::class);
            $this->expectExceptionCode(InvalidDataException::INVALID_DATA_TYPE);

            $rule->validate($data);
        } else {
            self::assertTrue($rule->validate($data));
        }
    }

    public function getTestableData(): array
    {
        return [
            [1, true],
            [1.0, true],
            ['1', false],
            ['number', false]
        ];
    }

    public function testGetType(): void
    {
        $rule = new NumericRule();
        self::assertSame(JsonRule::NUMERIC_TYPE, $rule->getType());
    }
}