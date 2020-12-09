<?php

namespace hunomina\DataValidator\Test\Rule\Json\Factory\TypedList;

use hunomina\DataValidator\Exception\Json\InvalidRuleException;
use hunomina\DataValidator\Rule\Json\Factory\JsonRuleFactory;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Rule\Json\TypedListRule;
use PHPUnit\Framework\TestCase;
use Throwable;

/**
 * Class ListEmptyRuleTest
 * @package hunomina\DataValidator\Test\Schema\Json\Rule\TypedList
 * @covers \hunomina\DataValidator\Rule\Json\Factory\JsonRuleFactory
 */
class ListEmptyRuleTest extends TestCase
{
    public function testThrowOnInvalidOptionValue(): void
    {
        try {
            JsonRuleFactory::create(JsonRule::STRING_LIST_TYPE, ['list-empty' => 'maybe']); // not boolean
        } catch (Throwable $t) {
            self::assertInstanceOf(InvalidRuleException::class, $t);
            self::assertEquals(InvalidRuleException::INVALID_LIST_EMPTY_RULE, $t->getCode());
        }
    }

    /**
     * @throws InvalidRuleException
     */
    public function testRuleCreation(): void
    {
        $empty = true;
        $rule = JsonRuleFactory::create(JsonRule::STRING_LIST_TYPE, ['list-empty' => $empty]);
        /** @var TypedListRule $rule */
        self::assertSame($empty, $rule->canBeEmpty());
    }
}