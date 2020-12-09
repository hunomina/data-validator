<?php

namespace hunomina\DataValidator\Test\Rule\Json;

use hunomina\DataValidator\Exception\Json\InvalidDataException;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Rule\Json\StringRule;
use PHPUnit\Framework\TestCase;

/**
 * Class StringRuleTest
 * @package hunomina\DataValidator\Test\Rule\Json
 * @covers \hunomina\DataValidator\Rule\Json\StringRule
 */
class StringRuleTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param $data
     * @param bool $success
     * @throws InvalidDataException
     */
    public function testStringType($data, bool $success): void
    {
        $rule = new StringRule();
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
            ['hello', true],
            [1, false]
        ];
    }

    public function testGetType(): void
    {
        $rule = new StringRule();
        self::assertSame(JsonRule::STRING_TYPE, $rule->getType());
    }
}