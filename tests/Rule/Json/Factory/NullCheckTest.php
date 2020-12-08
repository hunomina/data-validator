<?php

namespace hunomina\DataValidator\Test\Rule\Json\Factory;

use hunomina\DataValidator\Exception\Json\InvalidRuleException;
use hunomina\DataValidator\Rule\Json\Factory\JsonRuleFactory;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Rule\Json\StringRule;
use PHPUnit\Framework\TestCase;
use Throwable;

/**
 * Class NullCheckTest
 * @package hunomina\DataValidator\Test\Schema\Json\Rule
 * @covers \hunomina\DataValidator\Rule\Json\Factory\JsonRuleFactory
 */
class NullCheckTest extends TestCase
{
    /**
     * @param string $type
     * @dataProvider getTestableData
     */
    public function testTypeDoesNotSupportNullCheck(string $type): void
    {
        try {
            JsonRuleFactory::create($type, ['null' => true]);
        } catch (Throwable $t) {
            self::assertInstanceOf(InvalidRuleException::class, $t);
            self::assertEquals(InvalidRuleException::INVALID_NULL_RULE, $t->getCode());
        }
    }

    public function getTestableData(): array
    {
        return [
            [JsonRule::BOOLEAN_TYPE],
            [JsonRule::BOOLEAN_LIST_TYPE]
        ];
    }

    public function testThrowOnInvalidOptionValue(): void
    {
        try {
            JsonRuleFactory::create(JsonRule::STRING_TYPE, ['null' => 'maybe']); // not boolean
        } catch (Throwable $t) {
            self::assertInstanceOf(InvalidRuleException::class, $t);
            self::assertEquals(InvalidRuleException::INVALID_NULL_RULE, $t->getCode());
        }
    }

    /**
     * @throws InvalidRuleException
     */
    public function testRuleCreation(): void
    {
        $rule = JsonRuleFactory::create(JsonRule::STRING_TYPE, ['null' => true]);
        /** @var StringRule $rule */
        self::assertTrue($rule->canBeNull());
    }
}