<?php

namespace hunomina\DataValidator\Test\Rule\Json;

use hunomina\DataValidator\Exception\Json\InvalidDataException;
use hunomina\DataValidator\Rule\Json\CharacterRule;
use hunomina\DataValidator\Rule\Json\JsonRule;
use PHPUnit\Framework\TestCase;

/**
 * Class CharacterRuleTest
 * @package hunomina\DataValidator\Test\Rule\Json
 * @covers \hunomina\DataValidator\Rule\Json\CharacterRule
 */
class CharacterRuleTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param $data
     * @param bool $success
     * @throws InvalidDataException
     */
    public function testCharacterType($data, bool $success): void
    {
        $rule = new CharacterRule();
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
            ['a', true],
            ['abcde', false],
            [123, false],
        ];
    }

    public function testGetType(): void
    {
        $rule = new CharacterRule();
        self::assertSame(JsonRule::CHAR_TYPE, $rule->getType());
    }
}