<?php

namespace hunomina\DataValidator\Test\Rule\Json\Factory;

use hunomina\DataValidator\Exception\Json\InvalidRuleException;
use hunomina\DataValidator\Rule\Json\Factory\JsonRuleFactory;
use PHPUnit\Framework\TestCase;
use Throwable;

/**
 * Class TypeTest
 * @package hunomina\DataValidator\Test\Rule\Json\Factory
 * @covers \hunomina\DataValidator\Rule\Json\Factory\JsonRuleFactory
 */
class TypeTest extends TestCase
{
    public function testTypeDoesNotSupportListOption(): void
    {
        try {
            JsonRuleFactory::create('azertyuiop', []); // invalid type
        } catch (Throwable $t) {
            self::assertInstanceOf(InvalidRuleException::class, $t);
            self::assertEquals(InvalidRuleException::INVALID_RULE_TYPE, $t->getCode());
        }
    }
}