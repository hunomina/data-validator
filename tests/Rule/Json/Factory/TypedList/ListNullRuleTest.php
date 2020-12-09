<?php

namespace hunomina\DataValidator\Test\Rule\Json\Factory\TypedList;

use hunomina\DataValidator\Exception\Json\InvalidRuleException;
use hunomina\DataValidator\Rule\Json\Factory\JsonRuleFactory;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Rule\Json\TypedListRule;
use PHPUnit\Framework\TestCase;
use Throwable;

class ListNullRuleTest extends TestCase
{
    public function testThrowOnInvalidOptionValue(): void
    {
        try {
            JsonRuleFactory::create(JsonRule::STRING_LIST_TYPE, ['list-null' => 'maybe']); // not boolean
        } catch (Throwable $t) {
            self::assertInstanceOf(InvalidRuleException::class, $t);
            self::assertEquals(InvalidRuleException::INVALID_LIST_NULL_RULE, $t->getCode());
        }
    }

    /**
     * @throws InvalidRuleException
     */
    public function testRuleCreation(): void
    {
        $nullable = true;
        $rule = JsonRuleFactory::create(JsonRule::STRING_LIST_TYPE, ['list-null' => $nullable]);
        /** @var TypedListRule $rule */
        self::assertSame($nullable, $rule->canBeNull());
    }
}