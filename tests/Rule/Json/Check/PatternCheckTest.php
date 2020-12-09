<?php

namespace hunomina\DataValidator\Test\Rule\Json\Check;

use hunomina\DataValidator\Exception\Json\InvalidDataException;
use hunomina\DataValidator\Rule\Json\CharacterRule;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Rule\Json\StringRule;
use PHPUnit\Framework\TestCase;

/**
 * Class PatternCheckTest
 * @package hunomina\DataValidator\Test\Rule\Json\Traits
 */
class PatternCheckTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param JsonRule $rule
     * @param $data
     * @param bool $success
     * @throws InvalidDataException
     */
    public function testPatternCheck(JsonRule $rule, $data, bool $success): void
    {
        if (!$success) {
            $this->expectException(InvalidDataException::class);
            $this->expectExceptionCode(InvalidDataException::PATTERN_NOT_MATCHED);

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
            self::PatternStringCheck(),
            self::PatternStringCheckFail(),
            self::PatternCharCheck(),
            self::PatternCharCheckFail(),
        ];
    }

    /**
     * @return array
     * @covers \hunomina\DataValidator\Rule\Json\StringRule
     */
    private static function PatternStringCheck(): array
    {
        $rule = new StringRule();
        $rule->setPattern('/^[a-z]+$/');
        return [$rule, 'azertyuiop', true];
    }

    /**
     * @return array
     * @covers \hunomina\DataValidator\Rule\Json\StringRule
     */
    private static function PatternStringCheckFail(): array
    {
        $rule = new StringRule();
        $rule->setPattern('/^[a-z]+$/');
        return [$rule, '1234567890', false];
    }

    /**
     * @return array
     * @covers \hunomina\DataValidator\Rule\Json\CharacterRule
     */
    private static function PatternCharCheck(): array
    {
        $rule = new CharacterRule();
        $rule->setPattern('/^[a-z]$/');
        return [$rule, 'a', true];
    }

    /**
     * @return array
     * @covers \hunomina\DataValidator\Rule\Json\CharacterRule
     */
    private static function PatternCharCheckFail(): array
    {
        $rule = new CharacterRule();
        $rule->setPattern('/^[a-z]$/');
        return [$rule, '0', false];
    }
}