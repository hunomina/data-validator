<?php

namespace hunomina\DataValidator\Test\Rule\Json\Factory\TypedList;

use hunomina\DataValidator\Exception\Json\InvalidRuleException;
use hunomina\DataValidator\Rule\Json\Factory\JsonRuleFactory;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Rule\Json\TypedListRule;
use PHPUnit\Framework\TestCase;
use Throwable;

class ListMaxRuleTest extends TestCase
{
    public function testThrowOnInvalidOptionValue(): void
    {
        try {
            JsonRuleFactory::create(JsonRule::STRING_LIST_TYPE, ['list-max' => 'many']); // not integer
        } catch (Throwable $t) {
            self::assertInstanceOf(InvalidRuleException::class, $t);
            self::assertEquals(InvalidRuleException::INVALID_LIST_MAX_RULE, $t->getCode());
        }
    }

    /**
     * @throws InvalidRuleException
     */
    public function testRuleCreation(): void
    {
        $max = 10.0;
        $rule = JsonRuleFactory::create(JsonRule::STRING_LIST_TYPE, ['list-max' => $max]);
        /** @var TypedListRule $rule */
        self::assertSame($max, $rule->getMaximum());
    }
}