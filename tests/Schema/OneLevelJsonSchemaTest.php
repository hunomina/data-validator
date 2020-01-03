<?php

namespace hunomina\Validator\Json\Test\Schema;

use hunomina\Validator\Json\Exception\Json\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use PHPUnit\Framework\TestCase;

class OneLevelJsonSchemaTest extends TestCase
{
    /**
     * @throws InvalidSchemaException
     */
    public function testScalarRuleTypesOnSetSchema(): void
    {
        $schema = new JsonSchema([
            'boolean' => ['type' => JsonRule::BOOLEAN_TYPE],
            'string' => ['type' => JsonRule::STRING_TYPE],
            'integer' => ['type' => JsonRule::INTEGER_TYPE],
            'float' => ['type' => JsonRule::FLOAT_TYPE],
            'number' => ['type' => JsonRule::NUMERIC_TYPE],
            'character' => ['type' => JsonRule::CHAR_TYPE]
        ]);

        $this->assertCount(6, $schema->getRules());
        $this->assertCount(0, $schema->getChildren());

        $this->assertArrayHasKey('boolean', $schema->getRules());
        $this->assertEquals(JsonRule::BOOLEAN_TYPE, $schema->getRules()['boolean']->getType());

        $this->assertArrayHasKey('string', $schema->getRules());
        $this->assertEquals(JsonRule::STRING_TYPE, $schema->getRules()['string']->getType());

        $this->assertArrayHasKey('integer', $schema->getRules());
        $this->assertEquals(JsonRule::INTEGER_TYPE, $schema->getRules()['integer']->getType());

        $this->assertArrayHasKey('float', $schema->getRules());
        $this->assertEquals(JsonRule::FLOAT_TYPE, $schema->getRules()['float']->getType());

        $this->assertArrayHasKey('number', $schema->getRules());
        $this->assertEquals(JsonRule::NUMERIC_TYPE, $schema->getRules()['number']->getType());

        $this->assertArrayHasKey('character', $schema->getRules());
        $this->assertEquals(JsonRule::CHAR_TYPE, $schema->getRules()['character']->getType());
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testListRuleTypesOnSetSchema(): void
    {
        $schema = new JsonSchema([
            'boolean-list' => ['type' => JsonRule::BOOLEAN_LIST_TYPE],
            'string-list' => ['type' => JsonRule::STRING_LIST_TYPE],
            'integer-list' => ['type' => JsonRule::INTEGER_LIST_TYPE],
            'float-list' => ['type' => JsonRule::FLOAT_LIST_TYPE],
            'number-list' => ['type' => JsonRule::NUMERIC_LIST_TYPE],
            'character-list' => ['type' => JsonRule::CHAR_LIST_TYPE]
        ]);

        $this->assertCount(6, $schema->getRules());
        $this->assertCount(0, $schema->getChildren());

        $this->assertArrayHasKey('boolean-list', $schema->getRules());
        $this->assertEquals(JsonRule::BOOLEAN_LIST_TYPE, $schema->getRules()['boolean-list']->getType());

        $this->assertArrayHasKey('string-list', $schema->getRules());
        $this->assertEquals(JsonRule::STRING_LIST_TYPE, $schema->getRules()['string-list']->getType());

        $this->assertArrayHasKey('integer-list', $schema->getRules());
        $this->assertEquals(JsonRule::INTEGER_LIST_TYPE, $schema->getRules()['integer-list']->getType());

        $this->assertArrayHasKey('float-list', $schema->getRules());
        $this->assertEquals(JsonRule::FLOAT_LIST_TYPE, $schema->getRules()['float-list']->getType());

        $this->assertArrayHasKey('number-list', $schema->getRules());
        $this->assertEquals(JsonRule::NUMERIC_LIST_TYPE, $schema->getRules()['number-list']->getType());

        $this->assertArrayHasKey('character-list', $schema->getRules());
        $this->assertEquals(JsonRule::CHAR_LIST_TYPE, $schema->getRules()['character-list']->getType());
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testOptionalRuleOnSetSchema(): void
    {
        $schema = new JsonSchema([
            'optional' => ['type' => JsonRule::BOOLEAN_TYPE]
        ]);

        $schema2 = new JsonSchema([
            'optional' => ['type' => JsonRule::BOOLEAN_TYPE, 'optional' => true],
        ]);

        $this->assertCount(1, $schema->getRules());
        $this->assertCount(0, $schema->getChildren());

        $this->assertArrayHasKey('optional', $schema->getRules());
        $this->assertEquals(JsonRule::BOOLEAN_TYPE, $schema->getRules()['optional']->getType());
        $this->assertFalse($schema->getRules()['optional']->isOptional());

        $this->assertCount(1, $schema2->getRules());
        $this->assertCount(0, $schema2->getChildren());

        $this->assertArrayHasKey('optional', $schema2->getRules());
        $this->assertEquals(JsonRule::BOOLEAN_TYPE, $schema2->getRules()['optional']->getType());
        $this->assertTrue($schema2->getRules()['optional']->isOptional());
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testNullRuleOnSetSchema(): void
    {
        $schema = new JsonSchema([
            'null' => ['type' => JsonRule::BOOLEAN_TYPE]
        ]);

        $schema2 = new JsonSchema([
            'null' => ['type' => JsonRule::BOOLEAN_TYPE, 'null' => true],
        ]);

        $this->assertCount(1, $schema->getRules());
        $this->assertCount(0, $schema->getChildren());

        $this->assertArrayHasKey('null', $schema->getRules());
        $this->assertEquals(JsonRule::BOOLEAN_TYPE, $schema->getRules()['null']->getType());
        $this->assertFalse($schema->getRules()['null']->canBeNull());

        $this->assertCount(1, $schema2->getRules());
        $this->assertCount(0, $schema2->getChildren());

        $this->assertArrayHasKey('null', $schema2->getRules());
        $this->assertEquals(JsonRule::BOOLEAN_TYPE, $schema2->getRules()['null']->getType());
        $this->assertTrue($schema2->getRules()['null']->canBeNull());
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testThrowOnFieldWithoutAType(): void
    {
        $this->expectException(InvalidSchemaException::class);
        $this->expectExceptionCode(InvalidSchemaException::MISSING_TYPE);

        new JsonSchema([
            'no-type' => []
        ]);
    }
}