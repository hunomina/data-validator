<?php

namespace hunomina\DataValidator\Test\Rule\Json;

use hunomina\DataValidator\Exception\Json\InvalidDataException;
use hunomina\DataValidator\Rule\Json\BooleanRule;
use hunomina\DataValidator\Rule\Json\JsonRule;
use PHPUnit\Framework\TestCase;

/**
 * Class BooleanRuleTest
 * @package hunomina\DataValidator\Test\Rule\Json
 * @covers \hunomina\DataValidator\Rule\Json\BooleanRule
 */
class BooleanRuleTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param $data
     * @param bool $success
     * @throws InvalidDataException
     */
    public function testBooleanRule($data, bool $success): void
    {
        $rule = new BooleanRule();
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
            [true, true],
            ['not a boolean', false]
        ];
    }

    public function testGetType(): void
    {
        $rule = new BooleanRule();
        self::assertSame(JsonRule::BOOLEAN_TYPE, $rule->getType());
    }
}