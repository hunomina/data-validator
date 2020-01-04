<?php

namespace hunomina\Validator\Json\Test\Schema\Json;

use hunomina\Validator\Json\Exception\Json\InvalidSchemaException;
use hunomina\Validator\Json\Rule\Json\BooleanRule;
use hunomina\Validator\Json\Rule\Json\IntegerRule;
use hunomina\Validator\Json\Rule\Json\JsonRule;
use hunomina\Validator\Json\Rule\Json\StringRule;
use hunomina\Validator\Json\Schema\Json\JsonSchema;
use PHPUnit\Framework\TestCase;

class ThreeLevelJsonValidatorTest extends TestCase
{
    /**
     * @throws InvalidSchemaException
     */
    public function testThreeLevelSchema(): void
    {
        $schema = new JsonSchema([
            'boolean' => ['type' => JsonRule::BOOLEAN_TYPE],
            'string' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'list' => ['type' => JsonRule::LIST_TYPE, 'optional' => true, 'schema' => [
                'string' => ['type' => JsonRule::STRING_TYPE],
                'object' => ['type' => JsonRule::OBJECT_TYPE, 'null' => true, 'schema' => [
                    'string' => ['type' => JsonRule::STRING_TYPE],
                    'integer' => ['type' => JsonRule::INTEGER_TYPE, 'optional' => true],
                ]]
            ]]
        ]);

        $this->assertCount(2, $schema->getRules());
        $this->assertCount(1, $schema->getChildren());

        $this->assertArrayHasKey('boolean', $schema->getRules());
        $this->assertInstanceOf(BooleanRule::class, $schema->getRules()['boolean']);

        $this->assertArrayHasKey('string', $schema->getRules());
        $this->assertInstanceOf(StringRule::class, $schema->getRules()['string']);
        $this->assertTrue($schema->getRules()['string']->canBeNull());

        $this->assertArrayHasKey('list', $schema->getChildren());
        $listChild = $schema->getChildren()['list'];
        $this->assertEquals(JsonRule::LIST_TYPE, $listChild->getType());
        $this->assertTrue($listChild->isOptional());

        $this->assertCount(1, $listChild->getRules());
        $this->assertCount(1, $listChild->getChildren());

        $this->assertArrayHasKey('string', $listChild->getRules());
        $this->assertInstanceOf(StringRule::class, $listChild->getRules()['string']);

        $this->assertArrayHasKey('object', $listChild->getChildren());
        $objectChild = $listChild->getChildren()['object'];
        $this->assertEquals(JsonRule::OBJECT_TYPE, $objectChild->getType());
        $this->assertTrue($objectChild->canBeNull());

        $this->assertCount(2, $objectChild->getRules());
        $this->assertCount(0, $objectChild->getChildren());

        $this->assertArrayHasKey('string', $objectChild->getRules());
        $this->assertInstanceOf(StringRule::class, $objectChild->getRules()['string']);

        $this->assertArrayHasKey('integer', $objectChild->getRules());
        $this->assertInstanceOf(IntegerRule::class, $objectChild->getRules()['integer']);
        $this->assertTrue($objectChild->getRules()['integer']->isOptional());

    }
}