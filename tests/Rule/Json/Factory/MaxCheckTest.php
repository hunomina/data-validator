<?php

namespace hunomina\DataValidator\Test\Rule\Json\Factory;

use hunomina\DataValidator\Exception\Json\InvalidRuleException;
use hunomina\DataValidator\Rule\Json\Factory\JsonRuleFactory;
use hunomina\DataValidator\Rule\Json\IntegerRule;
use hunomina\DataValidator\Rule\Json\JsonRule;
use PHPUnit\Framework\TestCase;
use Throwable;

/**
 * Class MaxCheckTest
 * @package hunomina\DataValidator\Test\Schema\Json\Rule
 * @covers \hunomina\DataValidator\Rule\Json\Factory\JsonRuleFactory
 */
class MaxCheckTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param string $type
     */
    public function testTypeDoesNotSupportMaxCheck(string $type): void
    {
        try {
            JsonRuleFactory::create($type, ['max' => 10]);
        } catch (Throwable $t) {
            self::assertInstanceOf(InvalidRuleException::class, $t);
            self::assertEquals(InvalidRuleException::INVALID_MAX_RULE, $t->getCode());
        }
    }

    public function getTestableData(): array
    {
        return [
            [JsonRule::STRING_TYPE],
            [JsonRule::CHAR_TYPE],
            [JsonRule::BOOLEAN_TYPE],
            [JsonRule::STRING_LIST_TYPE],
            [JsonRule::CHAR_LIST_TYPE],
            [JsonRule::BOOLEAN_LIST_TYPE]
        ];
    }

    public function testThrowOnInvalidOptionValue(): void
    {
        try {
            JsonRuleFactory::create(JsonRule::INTEGER_TYPE, ['max' => 'many']); // not integer/float
        } catch (Throwable $t) {
            self::assertInstanceOf(InvalidRuleException::class, $t);
            self::assertEquals(InvalidRuleException::INVALID_MAX_RULE, $t->getCode());
        }
    }

    /**
     * @throws InvalidRuleException
     */
    public function testRuleCreation(): void
    {
        $max = 2.0;
        $rule = JsonRuleFactory::create(JsonRule::INTEGER_TYPE, ['max' => $max]);
        /** @var IntegerRule $rule */
        self::assertSame($max, $rule->getMaximum());
    }
}