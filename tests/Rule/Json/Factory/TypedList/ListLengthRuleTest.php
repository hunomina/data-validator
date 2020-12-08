<?php

namespace hunomina\DataValidator\Test\Rule\Json\Factory\TypedList;

use hunomina\DataValidator\Exception\Json\InvalidRuleException;
use hunomina\DataValidator\Rule\Json\Factory\JsonRuleFactory;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Rule\Json\TypedListRule;
use PHPUnit\Framework\TestCase;
use Throwable;

class ListLengthRuleTest extends TestCase
{
    public function testThrowOnInvalidOptionValue(): void
    {
        try {
            JsonRuleFactory::create(JsonRule::STRING_LIST_TYPE, ['list-length' => 'many']); // not integer
        } catch (Throwable $t) {
            self::assertInstanceOf(InvalidRuleException::class, $t);
            self::assertEquals(InvalidRuleException::INVALID_LIST_LENGTH_RULE, $t->getCode());
        }
    }

    /**
     * @throws InvalidRuleException
     */
    public function testRuleCreation(): void
    {
        $length = 10;
        $rule = JsonRuleFactory::create(JsonRule::STRING_LIST_TYPE, ['list-length' => $length]);
        /** @var TypedListRule $rule */
        self::assertSame($length, $rule->getLength());
    }
}