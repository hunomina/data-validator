<?php

namespace hunomina\DataValidator\Test\Schema\Json;

use hunomina\DataValidator\Exception\Json\InvalidSchemaException;
use hunomina\DataValidator\Rule\Json\BooleanRule;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Rule\Json\StringRule;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use PHPUnit\Framework\TestCase;
use Throwable;

class TwoLevelJsonSchemaWithListTest extends TestCase
{
    public function testChildListSchema(): void
    {
        $schema = new JsonSchema([
            'boolean' => ['type' => JsonRule::BOOLEAN_TYPE],
            'list' => ['type' => JsonRule::LIST_TYPE, 'schema' => [
                'boolean' => ['type' => JsonRule::BOOLEAN_TYPE, 'optional' => true],
                'string' => ['type' => JsonRule::STRING_TYPE, 'null' => true]
            ]]
        ]);

        $this->assertCount(1, $schema->getRules());
        $this->assertCount(1, $schema->getChildren());

        $this->assertArrayHasKey('boolean', $schema->getRules());
        $this->assertInstanceOf(BooleanRule::class, $schema->getRules()['boolean']);

        $this->assertArrayHasKey('list', $schema->getChildren());
        $listChild = $schema->getChildren()['list'];
        $this->assertEquals(JsonRule::LIST_TYPE, $listChild->getType());

        $this->assertCount(2, $listChild->getRules());
        $this->assertCount(0, $listChild->getChildren());

        $this->assertArrayHasKey('boolean', $listChild->getRules());
        $this->assertInstanceOf(BooleanRule::class, $listChild->getRules()['boolean']);
        $this->assertTrue($listChild->getRules()['boolean']->isOptional());

        $this->assertArrayHasKey('string', $listChild->getRules());
        /** @var StringRule $stringRule */
        $stringRule = $listChild->getRules()['string'];
        $this->assertInstanceOf(StringRule::class, $stringRule);
        $this->assertTrue($stringRule->canBeNull());
    }

    public function testThrowWithListFieldWithoutSchema(): void
    {
        try {
            new JsonSchema([
                'boolean' => ['type' => JsonRule::BOOLEAN_TYPE],
                'list' => ['type' => JsonRule::LIST_TYPE]
            ]);
        } catch (Throwable $t){
            $this->assertInstanceOf(InvalidSchemaException::class, $t);
            $this->assertEquals(InvalidSchemaException::INVALID_CHILD_SCHEMA, $t->getCode());

            $t = $t->getPrevious();
            $this->assertInstanceOf(InvalidSchemaException::class, $t);
            $this->assertEquals(InvalidSchemaException::MISSING_CHILD_SCHEMA, $t->getCode());
        }
    }
}