<?php

namespace hunomina\DataValidator\Test\Rule\Json\Factory;

use hunomina\DataValidator\Exception\Json\InvalidRuleException;
use hunomina\DataValidator\Rule\Json\Factory\JsonRuleFactory;
use hunomina\DataValidator\Rule\Json\JsonRule;
use PHPUnit\Framework\TestCase;
use Throwable;

/**
 * Class OptionalCheckTest
 * @package hunomina\DataValidator\Test\Rule\Json\Factory
 * @covers \hunomina\DataValidator\Rule\Json\Factory\JsonRuleFactory
 */
class OptionalCheckTest extends TestCase
{
    public function testTypeDoesNotSupportListOption(): void
    {
        try {
            JsonRuleFactory::create(JsonRule::STRING_TYPE, ['optional' => 'maybe']); // no boolean
        } catch (Throwable $t) {
            self::assertInstanceOf(InvalidRuleException::class, $t);
            self::assertEquals(InvalidRuleException::INVALID_OPTIONAL_RULE, $t->getCode());
        }
    }

    /**
     * @throws InvalidRuleException
     */
    public function testRuleCreation(): void
    {
        $rule = JsonRuleFactory::create(JsonRule::STRING_TYPE, ['optional' => true]);
        self::assertTrue($rule->isOptional());
    }
}