<?php

namespace hunomina\Validator\Json\Test\Rule;

use hunomina\Validator\Json\Data\JsonData;
use hunomina\Validator\Json\Exception\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use PHPUnit\Framework\TestCase;

class EmptyCheckTest extends TestCase
{
    /**
     * @throws InvalidSchemaException
     */
    public function testThrowOnInvalidTypeForEmptyRule(): void
    {
        $this->expectException(InvalidSchemaException::class);
        $this->expectExceptionCode(InvalidSchemaException::INVALID_EMPTY_RULE);

        new JsonSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::INTEGER_TYPE, 'empty' => false]
        ]);
    }

    /**
     * @throws InvalidSchemaException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    public function testSchemaWithEmptyRuleOnString(): void
    {
        $data = new JsonData([
            'name' => ''
        ]);

        $schema = new JsonSchema([
            'name' => ['type' => JsonRule::STRING_TYPE, 'empty' => false]
        ]);

        $schema2 = new JsonSchema([
            'name' => ['type' => JsonRule::STRING_TYPE, 'empty' => true] // empty: true is default
        ]);

        $this->assertFalse($schema->validate($data));
        $this->assertTrue($schema2->validate($data));
    }

    /**
     * @throws InvalidSchemaException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    public function testSchemaWithEmptyRuleOnStringList(): void
    {
        $data = new JsonData([
            'list' => []
        ]);

        $schema = new JsonSchema([
            'list' => ['type' => JsonRule::STRING_LIST_TYPE, 'empty' => false]
        ]);

        $schema2 = new JsonSchema([
            'list' => ['type' => JsonRule::STRING_LIST_TYPE, 'empty' => true] // empty: true is default
        ]);

        $this->assertFalse($schema->validate($data));
        $this->assertTrue($schema2->validate($data));
    }
}