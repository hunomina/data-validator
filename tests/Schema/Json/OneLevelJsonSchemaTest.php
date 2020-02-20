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

        $this->assertCount(6, $schema->getRules());
        $this->assertCount(0, $schema->getChildren());

        $this->assertArrayHasKey('boolean', $schema->getRules());
        $this->assertInstanceOf(BooleanRule::class, $schema->getRules()['boolean']);

        $this->assertArrayHasKey('string', $schema->getRules());
        $this->assertInstanceOf(StringRule::class, $schema->getRules()['string']);

        $this->assertArrayHasKey('integer', $schema->getRules());
        $this->assertInstanceOf(IntegerRule::class, $schema->getRules()['integer']);

        $this->assertArrayHasKey('float', $schema->getRules());
        $this->assertInstanceOf(FloatRule::class, $schema->getRules()['float']);

        $this->assertArrayHasKey('number', $schema->getRules());
        $this->assertInstanceOf(NumericRule::class, $schema->getRules()['number']);

        $this->assertArrayHasKey('character', $schema->getRules());
        $this->assertInstanceOf(CharacterRule::class, $schema->getRules()['character']);
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

        $this->assertCount(6, $schema->getRules());
        $this->assertCount(0, $schema->getChildren());

        $this->assertArrayHasKey('boolean-list', $schema->getRules());
        /** @var TypedListRule $booleanList */
        $booleanList = $schema->getRules()['boolean-list'];
        $this->assertInstanceOf(TypedListRule::class, $booleanList);
        $this->assertInstanceOf(BooleanRule::class, $booleanList->getChildRule());

        $this->assertArrayHasKey('string-list', $schema->getRules());
        /** @var TypedListRule $stringList */
        $stringList = $schema->getRules()['string-list'];
        $this->assertInstanceOf(TypedListRule::class, $stringList);
        $this->assertInstanceOf(StringRule::class, $stringList->getChildRule());

        $this->assertArrayHasKey('integer-list', $schema->getRules());
        /** @var TypedListRule $integerList */
        $integerList = $schema->getRules()['integer-list'];
        $this->assertInstanceOf(TypedListRule::class, $integerList);
        $this->assertInstanceOf(IntegerRule::class, $integerList->getChildRule());

        $this->assertArrayHasKey('float-list', $schema->getRules());
        /** @var TypedListRule $floatList */
        $floatList = $schema->getRules()['float-list'];
        $this->assertInstanceOf(TypedListRule::class, $floatList);
        $this->assertInstanceOf(FloatRule::class, $floatList->getChildRule());

        $this->assertArrayHasKey('number-list', $schema->getRules());
        /** @var TypedListRule $numberList */
        $numberList = $schema->getRules()['number-list'];
        $this->assertInstanceOf(TypedListRule::class, $numberList);
        $this->assertInstanceOf(NumericRule::class, $numberList->getChildRule());

        $this->assertArrayHasKey('character-list', $schema->getRules());
        /** @var TypedListRule $characterList */
        $characterList = $schema->getRules()['character-list'];
        $this->assertInstanceOf(TypedListRule::class, $characterList);
        $this->assertInstanceOf(CharacterRule::class, $characterList->getChildRule());
    }
    public function testOptionalRuleOnSetSchema(): void
    {
        $schema = new JsonSchema([
            'optional' => ['type' => JsonRule::STRING_TYPE]
        ]);

        $schema2 = new JsonSchema([
            'optional' => ['type' => JsonRule::STRING_TYPE, 'optional' => true],
        ]);

        $this->assertCount(1, $schema->getRules());
        $this->assertCount(0, $schema->getChildren());

        $this->assertArrayHasKey('optional', $schema->getRules());
        $this->assertInstanceOf(StringRule::class, $schema->getRules()['optional']);
        $this->assertFalse($schema->getRules()['optional']->isOptional());

        $this->assertCount(1, $schema2->getRules());
        $this->assertCount(0, $schema2->getChildren());

        $this->assertArrayHasKey('optional', $schema2->getRules());
        $this->assertInstanceOf(StringRule::class, $schema2->getRules()['optional']);
        $this->assertTrue($schema2->getRules()['optional']->isOptional());
    }
    public function testNullRuleOnSetSchema(): void
    {
        $schema = new JsonSchema([
            'null' => ['type' => JsonRule::STRING_TYPE]
        ]);

        $schema2 = new JsonSchema([
            'null' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
        ]);

        $this->assertCount(1, $schema->getRules());
        $this->assertCount(0, $schema->getChildren());

        $this->assertArrayHasKey('null', $schema->getRules());
        $this->assertInstanceOf(StringRule::class, $schema->getRules()['null']);
        $this->assertFalse($schema->getRules()['null']->canBeNull());

        $this->assertCount(1, $schema2->getRules());
        $this->assertCount(0, $schema2->getChildren());

        $this->assertArrayHasKey('null', $schema2->getRules());
        $this->assertInstanceOf(StringRule::class, $schema2->getRules()['null']);
        $this->assertTrue($schema2->getRules()['null']->canBeNull());
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