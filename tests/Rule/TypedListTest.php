<?php

namespace hunomina\Validator\Json\Test\Rule;

use hunomina\Validator\Json\Data\JsonData;
use hunomina\Validator\Json\Exception\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use PHPUnit\Framework\TestCase;

class TypedListTest extends TestCase
{
    /**
     * @throws InvalidSchemaException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    public function testIntList(): void
    {
        $data = new JsonData([
            'users' => [1, 2, 3, 4]
        ]);

        $data2 = new JsonData([
            'users' => [1, 2.89, 3.14158, 4.0]
        ]);

        $schema = new JsonSchema([
            'users' => ['type' => JsonRule::INTEGER_LIST_TYPE]
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    public function testStringList(): void
    {
        $data = new JsonData([
            'users' => ['hello', 'i', 'am', 'testing']
        ]);

        $data2 = new JsonData([
            'users' => ['hello', 'i', 'am', 'testing', 'for', 'the', 2, 'time']
        ]);

        $schema = new JsonSchema([
            'users' => ['type' => JsonRule::STRING_LIST_TYPE]
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    public function testCharList(): void
    {
        $data = new JsonData([
            'users' => ['a', 'b', 'c', 'd']
        ]);

        $data2 = new JsonData([
            'users' => ['a', 'bc', 'd', 'e']
        ]);

        $schema = new JsonSchema([
            'users' => ['type' => JsonRule::CHAR_LIST_TYPE]
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    public function testBooleanList(): void
    {
        $data = new JsonData([
            'users' => [true, false, false, true]
        ]);

        $data2 = new JsonData([
            'users' => [true, false, 0, true]
        ]);

        $schema = new JsonSchema([
            'users' => ['type' => JsonRule::BOOLEAN_LIST_TYPE]
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    public function testFloatList(): void
    {
        $data = new JsonData([
            'users' => [1.1, 2.2, 3.3, 4.4]
        ]);

        $data2 = new JsonData([
            'users' => [1, 2.2, 3.3, 4.4]
        ]);

        $schema = new JsonSchema([
            'users' => ['type' => JsonRule::FLOAT_LIST_TYPE]
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    public function testNumericList(): void
    {
        $data = new JsonData([
            'users' => [1, 2.89, 3.14158, 4.0]
        ]);

        $data2 = new JsonData([
            'users' => ['a', 2.89, 3.14158, 4.0]
        ]);

        $schema = new JsonSchema([
            'users' => ['type' => JsonRule::NUMERIC_LIST_TYPE]
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidDataTypeException
     * Assertion should fail because the `random-list` type simply does not exist
     */
    public function testWrongTypeList(): void
    {
        $data = new JsonData([
            'users' => [1, 2.89, 3.14158, 4.0]
        ]);

        $schema = new JsonSchema([
            'users' => ['type' => 'random-list']
        ]);

        $this->assertFalse($schema->validate($data));
    }
}