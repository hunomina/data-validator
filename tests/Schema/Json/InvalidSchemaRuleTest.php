<?php

namespace hunomina\DataValidator\Test\Schema\Json;

use hunomina\DataValidator\Exception\Json\InvalidRuleException;
use hunomina\DataValidator\Exception\Json\InvalidSchemaException;
use hunomina\DataValidator\Rule\Json\Factory\JsonRuleFactory;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use PHPUnit\Framework\TestCase;
use Throwable;

/**
 * Class InvalidSchemaRuleTest
 * @package hunomina\DataValidator\Test\Schema\Json
 * @covers \hunomina\DataValidator\Schema\Json\JsonSchema
 */
class InvalidSchemaRuleTest extends TestCase
{
    public function testSchemaWrapRuleFactoryException(): void
    {
        $type = JsonRule::CHAR_TYPE;
        $options = ['min' => 2];

        $factoryException = null;
        try {
            JsonRuleFactory::create($type, $options);
        } catch (InvalidRuleException $e) {
            $factoryException = $e;
        }

        self::assertInstanceOf(InvalidRuleException::class, $factoryException);
        self::assertSame(InvalidRuleException::INVALID_MIN_RULE, $factoryException->getCode());

        try {
            new JsonSchema([
                'test' => array_merge(['type' => $type], $options) // invalid char rule
            ]);
        } catch (Throwable $t) {
            self::assertInstanceOf(InvalidSchemaException::class, $t);
            self::assertEquals(InvalidSchemaException::INVALID_SCHEMA_RULE, $t->getCode());

            $previous = $t->getPrevious();
            self::assertInstanceOf(InvalidRuleException::class, $previous);
            self::assertEquals($factoryException, $previous);
        }
    }

    public function testThrowOnFieldWithoutAType(): void
    {
        $this->expectException(InvalidSchemaException::class);
        $this->expectExceptionCode(InvalidSchemaException::MISSING_RULE_TYPE);

        new JsonSchema([
            'no-type' => []
        ]);
    }
}