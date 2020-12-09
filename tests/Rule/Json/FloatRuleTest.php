<?php

namespace hunomina\DataValidator\Test\Rule\Json;

use hunomina\DataValidator\Exception\Json\InvalidDataException;
use hunomina\DataValidator\Rule\Json\FloatRule;
use hunomina\DataValidator\Rule\Json\JsonRule;
use PHPUnit\Framework\TestCase;

/**
 * Class FloatRuleTest
 * @package hunomina\DataValidator\Test\Rule\Json
 * @covers \hunomina\DataValidator\Rule\Json\FloatRule
 */
class FloatRuleTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param FloatRule $rule
     * @param $data
     * @param bool $success
     * @param int|null $expectedExceptionCode
     * @throws InvalidDataException
     */
    public function testFloatType(FloatRule $rule, $data, bool $success = true, ?int $expectedExceptionCode = null): void
    {
        if (!$success) {
            $this->expectException(InvalidDataException::class);
            $this->expectExceptionCode($expectedExceptionCode);

            $rule->validate($data);
        } else {
            self::assertTrue($rule->validate($data));
        }
    }

    public function getTestableData(): array
    {
        return [
            [new FloatRule(), 1.0],
            [new FloatRule(), 1, false, InvalidDataException::INVALID_DATA_TYPE]
        ];
    }

    public function testGetType(): void
    {
        $rule = new FloatRule();
        self::assertSame(JsonRule::FLOAT_TYPE, $rule->getType());
    }
}