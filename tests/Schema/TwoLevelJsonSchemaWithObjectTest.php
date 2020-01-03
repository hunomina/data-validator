<?php

namespace hunomina\Validator\Json\Test\Schema;

use hunomina\Validator\Json\Exception\Json\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use PHPUnit\Framework\TestCase;

class TwoLevelJsonSchemaWithObjectTest extends TestCase
{
    /**
     * @throws InvalidSchemaException
     */
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
        $this->assertEquals(JsonRule::BOOLEAN_TYPE, $schema->getRules()['boolean']->getType());

        $this->assertArrayHasKey('object', $schema->getChildren());
        $objectChild = $schema->getChildren()['object'];
        $this->assertEquals(JsonRule::OBJECT_TYPE, $objectChild->getType());

        $this->assertCount(2, $objectChild->getRules());
        $this->assertCount(0, $objectChild->getChildren());

        $this->assertArrayHasKey('integer', $objectChild->getRules());
        $this->assertEquals(JsonRule::INTEGER_TYPE, $objectChild->getRules()['integer']->getType());
        $this->assertTrue($objectChild->getRules()['integer']->canBeNull());

        $this->assertArrayHasKey('string', $objectChild->getRules());
        $this->assertEquals(JsonRule::STRING_TYPE, $objectChild->getRules()['string']->getType());
        $this->assertTrue($objectChild->getRules()['string']->isOptional());
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testThrowWithObjectFieldWithoutSchema(): void
    {
        $this->expectExceptionCode(InvalidSchemaException::class);
        $this->expectExceptionCode(InvalidSchemaException::MISSING_SCHEMA);

        new JsonSchema([
            'boolean' => ['type' => JsonRule::BOOLEAN_TYPE],
            'object' => ['type' => JsonRule::OBJECT_TYPE]
        ]);
    }
}