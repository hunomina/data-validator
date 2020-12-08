<?php

namespace hunomina\DataValidator\Test\Schema\Json;

use hunomina\DataValidator\Exception\Json\InvalidSchemaException;
use hunomina\DataValidator\Rule\Json\BooleanRule;
use hunomina\DataValidator\Rule\Json\CharacterRule;
use hunomina\DataValidator\Rule\Json\FloatRule;
use hunomina\DataValidator\Rule\Json\IntegerRule;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Rule\Json\NumericRule;
use hunomina\DataValidator\Rule\Json\StringRule;
use hunomina\DataValidator\Rule\Json\TypedListRule;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use PHPUnit\Framework\TestCase;

/**
 * Class OneLevelJsonSchemaTest
 * @package hunomina\DataValidator\Test\Schema\Json
 * @covers \hunomina\DataValidator\Schema\Json\JsonSchema
 */
class OneLevelJsonSchemaTest extends TestCase
{
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

        self::assertCount(6, $schema->getRules());
        self::assertCount(0, $schema->getChildren());

        self::assertArrayHasKey('boolean', $schema->getRules());
        self::assertInstanceOf(BooleanRule::class, $schema->getRules()['boolean']);

        self::assertArrayHasKey('string', $schema->getRules());
        self::assertInstanceOf(StringRule::class, $schema->getRules()['string']);

        self::assertArrayHasKey('integer', $schema->getRules());
        self::assertInstanceOf(IntegerRule::class, $schema->getRules()['integer']);

        self::assertArrayHasKey('float', $schema->getRules());
        self::assertInstanceOf(FloatRule::class, $schema->getRules()['float']);

        self::assertArrayHasKey('number', $schema->getRules());
        self::assertInstanceOf(NumericRule::class, $schema->getRules()['number']);

        self::assertArrayHasKey('character', $schema->getRules());
        self::assertInstanceOf(CharacterRule::class, $schema->getRules()['character']);
    }

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

        self::assertCount(6, $schema->getRules());
        self::assertCount(0, $schema->getChildren());

        self::assertArrayHasKey('boolean-list', $schema->getRules());
        /** @var TypedListRule $booleanList */
        $booleanList = $schema->getRules()['boolean-list'];
        self::assertInstanceOf(TypedListRule::class, $booleanList);
        self::assertInstanceOf(BooleanRule::class, $booleanList->getChildRule());

        self::assertArrayHasKey('string-list', $schema->getRules());
        /** @var TypedListRule $stringList */
        $stringList = $schema->getRules()['string-list'];
        self::assertInstanceOf(TypedListRule::class, $stringList);
        self::assertInstanceOf(StringRule::class, $stringList->getChildRule());

        self::assertArrayHasKey('integer-list', $schema->getRules());
        /** @var TypedListRule $integerList */
        $integerList = $schema->getRules()['integer-list'];
        self::assertInstanceOf(TypedListRule::class, $integerList);
        self::assertInstanceOf(IntegerRule::class, $integerList->getChildRule());

        self::assertArrayHasKey('float-list', $schema->getRules());
        /** @var TypedListRule $floatList */
        $floatList = $schema->getRules()['float-list'];
        self::assertInstanceOf(TypedListRule::class, $floatList);
        self::assertInstanceOf(FloatRule::class, $floatList->getChildRule());

        self::assertArrayHasKey('number-list', $schema->getRules());
        /** @var TypedListRule $numberList */
        $numberList = $schema->getRules()['number-list'];
        self::assertInstanceOf(TypedListRule::class, $numberList);
        self::assertInstanceOf(NumericRule::class, $numberList->getChildRule());

        self::assertArrayHasKey('character-list', $schema->getRules());
        /** @var TypedListRule $characterList */
        $characterList = $schema->getRules()['character-list'];
        self::assertInstanceOf(TypedListRule::class, $characterList);
        self::assertInstanceOf(CharacterRule::class, $characterList->getChildRule());
    }

    public function testOptionalRuleOnSetSchema(): void
    {
        $schema = new JsonSchema([
            'optional' => ['type' => JsonRule::STRING_TYPE]
        ]);

        $schema2 = new JsonSchema([
            'optional' => ['type' => JsonRule::STRING_TYPE, 'optional' => true],
        ]);

        self::assertCount(1, $schema->getRules());
        self::assertCount(0, $schema->getChildren());

        self::assertArrayHasKey('optional', $schema->getRules());
        self::assertInstanceOf(StringRule::class, $schema->getRules()['optional']);
        self::assertFalse($schema->getRules()['optional']->isOptional());

        self::assertCount(1, $schema2->getRules());
        self::assertCount(0, $schema2->getChildren());

        self::assertArrayHasKey('optional', $schema2->getRules());
        self::assertInstanceOf(StringRule::class, $schema2->getRules()['optional']);
        self::assertTrue($schema2->getRules()['optional']->isOptional());
    }

    public function testNullRuleOnSetSchema(): void
    {
        $schema = new JsonSchema([
            'null' => ['type' => JsonRule::STRING_TYPE]
        ]);

        $schema2 = new JsonSchema([
            'null' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
        ]);

        self::assertCount(1, $schema->getRules());
        self::assertCount(0, $schema->getChildren());

        self::assertArrayHasKey('null', $schema->getRules());
        self::assertInstanceOf(StringRule::class, $schema->getRules()['null']);
        self::assertFalse($schema->getRules()['null']->canBeNull());

        self::assertCount(1, $schema2->getRules());
        self::assertCount(0, $schema2->getChildren());

        self::assertArrayHasKey('null', $schema2->getRules());
        self::assertInstanceOf(StringRule::class, $schema2->getRules()['null']);
        self::assertTrue($schema2->getRules()['null']->canBeNull());
    }
}