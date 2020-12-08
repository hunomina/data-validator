<?php

namespace hunomina\DataValidator\Test\Rule\Json\Factory;

use hunomina\DataValidator\Exception\Json\InvalidRuleException;
use hunomina\DataValidator\Rule\Json\Factory\JsonRuleFactory;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Rule\Json\StringRule;
use PHPUnit\Framework\TestCase;
use Throwable;

/**
 * Class EmptyCheckTest
 * @package hunomina\DataValidator\Test\Schema\Json\Rule
 * @covers \hunomina\DataValidator\Rule\Json\Factory\JsonRuleFactory
 */
class EmptyCheckTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param string $type
     */
    public function testTypeDoesNotSupportEmptyCheck(string $type): void
    {
        try {
            JsonRuleFactory::create($type, ['empty' => true]);
        } catch (Throwable $t) {
            self::assertInstanceOf(InvalidRuleException::class, $t);
            self::assertEquals(InvalidRuleException::INVALID_EMPTY_RULE, $t->getCode());
        }
    }

    public function getTestableData(): array
    {
        return [
            [JsonRule::CHAR_TYPE],
            [JsonRule::NUMERIC_TYPE],
            [JsonRule::INTEGER_TYPE],
            [JsonRule::FLOAT_TYPE],
            [JsonRule::BOOLEAN_TYPE],
            [JsonRule::CHAR_LIST_TYPE],
            [JsonRule::NUMERIC_LIST_TYPE],
            [JsonRule::INTEGER_LIST_TYPE],
            [JsonRule::FLOAT_LIST_TYPE],
            [JsonRule::BOOLEAN_LIST_TYPE]
        ];
    }

    public function testThrowOnInvalidOptionValue(): void
    {
        try {
            JsonRuleFactory::create(JsonRule::STRING_TYPE, ['empty' => 'maybe']); // not boolean
        } catch (Throwable $t) {
            self::assertInstanceOf(InvalidRuleException::class, $t);
            self::assertEquals(InvalidRuleException::INVALID_EMPTY_RULE, $t->getCode());
        }
    }

    /**
     * @throws InvalidRuleException
     */
    public function testRuleCreation(): void
    {
        $empty = true;
        $rule = JsonRuleFactory::create(JsonRule::STRING_TYPE, ['empty' => $empty]);
        /** @var StringRule $rule */
        self::assertSame($empty, $rule->canBeEmpty());
    }
}