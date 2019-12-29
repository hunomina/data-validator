<?php

namespace hunomina\Validator\Json\Test\Schema;

use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use PHPUnit\Framework\TestCase;

class TwoLevelJsonSchemaWithListTest extends TestCase
{
    /**
     * @throws InvalidSchemaException
     */
    public function testChildListSchema(): void
    {
        $schema = new JsonSchema([
            'boolean' => ['type' => JsonRule::BOOLEAN_TYPE],
            'list' => ['type' => JsonRule::LIST_TYPE, 'schema' => [
                'boolean' => ['type' => JsonRule::BOOLEAN_TYPE, 'null' => true],
                'string' => ['type' => JsonRule::STRING_TYPE, 'optional' => true]
            ]]
        ]);

        $this->assertCount(1, $schema->getRules());
        $this->assertCount(1, $schema->getChildren());

        $this->assertArrayHasKey('boolean', $schema->getRules());
        $this->assertEquals(JsonRule::BOOLEAN_TYPE, $schema->getRules()['boolean']->getType());

        $this->assertArrayHasKey('list', $schema->getChildren());
        $listChild = $schema->getChildren()['list'];
        $this->assertEquals(JsonRule::LIST_TYPE, $listChild->getType());

        $this->assertCount(2, $listChild->getRules());
        $this->assertCount(0, $listChild->getChildren());

        $this->assertArrayHasKey('boolean', $listChild->getRules());
        $this->assertEquals(JsonRule::BOOLEAN_TYPE, $listChild->getRules()['boolean']->getType());
        $this->assertTrue($listChild->getRules()['boolean']->canBeNull());

        $this->assertArrayHasKey('string', $listChild->getRules());
        $this->assertEquals(JsonRule::STRING_TYPE, $listChild->getRules()['string']->getType());
        $this->assertTrue($listChild->getRules()['string']->isOptional());
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testThrowWithListFieldWithoutSchema(): void
    {
        $this->expectExceptionCode(InvalidSchemaException::class);
        $this->expectExceptionCode(InvalidSchemaException::MISSING_SCHEMA);

        new JsonSchema([
            'boolean' => ['type' => JsonRule::BOOLEAN_TYPE],
            'list' => ['type' => JsonRule::LIST_TYPE]
        ]);
    }
}