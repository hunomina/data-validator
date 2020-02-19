<?php

namespace hunomina\DataValidator\Test\Schema\Json;

use hunomina\DataValidator\Exception\Json\InvalidSchemaException;
use hunomina\DataValidator\Rule\Json\BooleanRule;
use hunomina\DataValidator\Rule\Json\IntegerRule;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Rule\Json\StringRule;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use PHPUnit\Framework\TestCase;

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

        $this->assertCount(1, $schema->getRules());
        $this->assertCount(1, $schema->getChildren());

        $this->assertArrayHasKey('boolean', $schema->getRules());
        $this->assertInstanceOf(BooleanRule::class, $schema->getRules()['boolean']);

        $this->assertArrayHasKey('object', $schema->getChildren());
        $objectChild = $schema->getChildren()['object'];
        $this->assertEquals(JsonRule::OBJECT_TYPE, $objectChild->getType());

        $this->assertCount(2, $objectChild->getRules());
        $this->assertCount(0, $objectChild->getChildren());

        $this->assertArrayHasKey('integer', $objectChild->getRules());
        $this->assertInstanceOf(IntegerRule::class, $objectChild->getRules()['integer']);
        $this->assertTrue($objectChild->getRules()['integer']->canBeNull());

        $this->assertArrayHasKey('string', $objectChild->getRules());
        $this->assertInstanceOf(StringRule::class, $objectChild->getRules()['string']);
        $this->assertTrue($objectChild->getRules()['string']->isOptional());
    }
    public function testThrowWithObjectFieldWithoutSchema(): void
    {
        $this->expectExceptionCode(InvalidSchemaException::class);
        $this->expectExceptionCode(InvalidSchemaException::MISSING_CHILD_SCHEMA);

        new JsonSchema([
            'boolean' => ['type' => JsonRule::BOOLEAN_TYPE],
            'object' => ['type' => JsonRule::OBJECT_TYPE]
        ]);
    }
}