<?php

namespace hunomina\DataValidator\Test\Schema\Json;

use hunomina\DataValidator\Rule\Json\BooleanRule;
use hunomina\DataValidator\Rule\Json\IntegerRule;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Rule\Json\StringRule;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use PHPUnit\Framework\TestCase;

class ThreeLevelJsonValidatorTest extends TestCase
{
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

        self::assertCount(2, $schema->getRules());
        self::assertCount(1, $schema->getChildren());

        self::assertArrayHasKey('boolean', $schema->getRules());
        self::assertInstanceOf(BooleanRule::class, $schema->getRules()['boolean']);

        self::assertArrayHasKey('string', $schema->getRules());
        self::assertInstanceOf(StringRule::class, $schema->getRules()['string']);
        self::assertTrue($schema->getRules()['string']->canBeNull());

        self::assertArrayHasKey('list', $schema->getChildren());
        $listChild = $schema->getChildren()['list'];
        self::assertEquals(JsonRule::LIST_TYPE, $listChild->getType());
        self::assertTrue($listChild->isOptional());

        self::assertCount(1, $listChild->getRules());
        self::assertCount(1, $listChild->getChildren());

        self::assertArrayHasKey('string', $listChild->getRules());
        self::assertInstanceOf(StringRule::class, $listChild->getRules()['string']);

        self::assertArrayHasKey('object', $listChild->getChildren());
        $objectChild = $listChild->getChildren()['object'];
        self::assertEquals(JsonRule::OBJECT_TYPE, $objectChild->getType());
        self::assertTrue($objectChild->canBeNull());

        self::assertCount(2, $objectChild->getRules());
        self::assertCount(0, $objectChild->getChildren());

        self::assertArrayHasKey('string', $objectChild->getRules());
        self::assertInstanceOf(StringRule::class, $objectChild->getRules()['string']);

        self::assertArrayHasKey('integer', $objectChild->getRules());
        self::assertInstanceOf(IntegerRule::class, $objectChild->getRules()['integer']);
        self::assertTrue($objectChild->getRules()['integer']->isOptional());

    }
}