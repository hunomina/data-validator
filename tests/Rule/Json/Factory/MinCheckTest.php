<?php

namespace hunomina\DataValidator\Test\Rule\Json\Factory;

use hunomina\DataValidator\Exception\Json\InvalidRuleException;
use hunomina\DataValidator\Rule\Json\Factory\JsonRuleFactory;
use hunomina\DataValidator\Rule\Json\IntegerRule;
use hunomina\DataValidator\Rule\Json\JsonRule;
use PHPUnit\Framework\TestCase;
use Throwable;

/**
 * Class MinCheckTest
 * @package hunomina\DataValidator\Test\Schema\Json\Rule
 * @covers \hunomina\DataValidator\Rule\Json\Factory\JsonRuleFactory
 */
class MinCheckTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param string $type
     */
    public function testTypeDoesNotSupportMinCheck(string $type): void
    {
        try {
            JsonRuleFactory::create($type, ['min' => 1]);
        } catch (Throwable $t) {
            self::assertInstanceOf(InvalidRuleException::class, $t);
            self::assertEquals(InvalidRuleException::INVALID_MIN_RULE, $t->getCode());
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
            JsonRuleFactory::create(JsonRule::INTEGER_TYPE, ['min' => 'many']); // not integer/float
        } catch (Throwable $t) {
            self::assertInstanceOf(InvalidRuleException::class, $t);
            self::assertEquals(InvalidRuleException::INVALID_MIN_RULE, $t->getCode());
        }
    }

    /**
     * @throws InvalidRuleException
     */
    public function testRuleCreation(): void
    {
        $min = 2.0;
        $rule = JsonRuleFactory::create(JsonRule::INTEGER_TYPE, ['min' => $min]);
        /** @var IntegerRule $rule */
        self::assertSame($min, $rule->getMinimum());
    }

    public function testMinOptionIntegerValueCastToFloat(): void
    {
        $min = 2;
        $rule = new IntegerRule();
        $rule->setMinimum($min);
        self::assertSame((float)$min, $rule->getMinimum());
    }
}