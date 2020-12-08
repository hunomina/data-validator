<?php

namespace hunomina\DataValidator\Test\Rule\Json\Factory;

use hunomina\DataValidator\Exception\Json\InvalidRuleException;
use hunomina\DataValidator\Rule\Json\Factory\JsonRuleFactory;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Rule\Json\StringRule;
use PHPUnit\Framework\TestCase;
use Throwable;

/**
 * Class PatternCheckTest
 * @package hunomina\DataValidator\Test\Schema\Json\Rule
 * @covers \hunomina\DataValidator\Rule\Json\Factory\JsonRuleFactory
 */
class PatternCheckTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param string $type
     */
    public function testTypeDoesNotSupportPatternCheck(string $type): void
    {
        try {
            JsonRuleFactory::create($type, ['pattern' => '/\d+/']);
        } catch (Throwable $t) {
            self::assertInstanceOf(InvalidRuleException::class, $t);
            self::assertEquals(InvalidRuleException::INVALID_PATTERN_RULE, $t->getCode());
        }
    }

    public function getTestableData(): array
    {
        return [
            [JsonRule::NUMERIC_TYPE],
            [JsonRule::INTEGER_TYPE],
            [JsonRule::FLOAT_TYPE],
            [JsonRule::BOOLEAN_TYPE],
            [JsonRule::NUMERIC_LIST_TYPE],
            [JsonRule::INTEGER_LIST_TYPE],
            [JsonRule::FLOAT_LIST_TYPE],
            [JsonRule::BOOLEAN_LIST_TYPE]
        ];
    }

    public function testThrowOnInvalidOptionValue(): void
    {
        try {
            JsonRuleFactory::create(JsonRule::STRING_TYPE, ['pattern' => false]); // not string
        } catch (Throwable $t) {
            self::assertInstanceOf(InvalidRuleException::class, $t);
            self::assertEquals(InvalidRuleException::INVALID_PATTERN_RULE, $t->getCode());
        }
    }

    /**
     * @throws InvalidRuleException
     */
    public function testRuleCreation(): void
    {
        $pattern = '/^test$/';
        $rule = JsonRuleFactory::create(JsonRule::STRING_TYPE, ['pattern' => $pattern]);
        /** @var StringRule $rule */
        self::assertSame($pattern, $rule->getPattern());
    }
}