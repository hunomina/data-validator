<?php

namespace hunomina\DataValidator\Test\Rule\Json;

use hunomina\DataValidator\Exception\Json\InvalidDataException;
use hunomina\DataValidator\Rule\Json\IntegerRule;
use hunomina\DataValidator\Rule\Json\JsonRule;
use PHPUnit\Framework\TestCase;

/**
 * Class IntegerRuleTest
 * @package hunomina\DataValidator\Test\Rule\Json
 * @covers \hunomina\DataValidator\Rule\Json\IntegerRule
 */
class IntegerRuleTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param $data
     * @param bool $success
     * @throws InvalidDataException
     */
    public function testIntegerType($data, bool $success): void
    {
        $rule = new IntegerRule();
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
            ['not an integer', false]
        ];
    }

    public function testGetType(): void
    {
        $rule = new IntegerRule();
        self::assertSame(JsonRule::INTEGER_TYPE, $rule->getType());
    }
}