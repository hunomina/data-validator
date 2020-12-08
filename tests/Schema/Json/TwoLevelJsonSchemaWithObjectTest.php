<?php

namespace hunomina\DataValidator\Test\Schema\Json;

use hunomina\DataValidator\Exception\Json\InvalidSchemaException;
use hunomina\DataValidator\Rule\Json\BooleanRule;
use hunomina\DataValidator\Rule\Json\IntegerRule;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Rule\Json\StringRule;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use PHPUnit\Framework\TestCase;
use Throwable;

/**
 * Class TwoLevelJsonSchemaWithObjectTest
 * @package hunomina\DataValidator\Test\Schema\Json
 * @covers \hunomina\DataValidator\Schema\Json\JsonSchema
 */
class TwoLevelJsonSchemaWithObjectTest extends TestCase
{
    public function testChildObjectSchema(): void
    {
        $schema = new JsonSchema([
            'boolean' => ['type' => JsonRule::BOOLEAN_TYPE],
            'object' => ['type' => JsonRule::OBJECT_TYPE, 'schema' => [
                'integer' => ['type' => JsonRule::INTEGER_TYPE, 'null' => true],
                'string' => ['type' => JsonRule::STRING_TYPE, 'optional' => true]
            ]]
        ]);

        self::assertCount(1, $schema->getRules());
        self::assertCount(1, $schema->getChildren());

        self::assertArrayHasKey('boolean', $schema->getRules());
        self::assertInstanceOf(BooleanRule::class, $schema->getRules()['boolean']);

        self::assertArrayHasKey('object', $schema->getChildren());
        $objectChild = $schema->getChildren()['object'];
        self::assertEquals(JsonRule::OBJECT_TYPE, $objectChild->getType());

        self::assertCount(2, $objectChild->getRules());
        self::assertCount(0, $objectChild->getChildren());

        self::assertArrayHasKey('integer', $objectChild->getRules());
        self::assertInstanceOf(IntegerRule::class, $objectChild->getRules()['integer']);
        self::assertTrue($objectChild->getRules()['integer']->canBeNull());

        self::assertArrayHasKey('string', $objectChild->getRules());
        self::assertInstanceOf(StringRule::class, $objectChild->getRules()['string']);
        self::assertTrue($objectChild->getRules()['string']->isOptional());
    }

    public function testThrowWithObjectFieldWithoutSchema(): void
    {
        try {
            new JsonSchema([
                'boolean' => ['type' => JsonRule::BOOLEAN_TYPE],
                'object' => ['type' => JsonRule::OBJECT_TYPE]
            ]);
        } catch (Throwable $t) {
            self::assertInstanceOf(InvalidSchemaException::class, $t);
            self::assertEquals(InvalidSchemaException::INVALID_CHILD_SCHEMA, $t->getCode());

            $t = $t->getPrevious();
            self::assertInstanceOf(InvalidSchemaException::class, $t);
            self::assertEquals(InvalidSchemaException::MISSING_CHILD_SCHEMA, $t->getCode());
        }
    }
}