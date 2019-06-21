<?php

namespace hunomina\Validator\Json\Test;

use hunomina\Validator\Json\Data\JsonData;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Schema\JsonSchema;
use PHPUnit\Framework\TestCase;

class TypedListTest extends TestCase
{
    /**
     * @throws InvalidSchemaException
     */
    public function testIntList(): void
    {
        $data = (new JsonData())->setDataFromArray([
            'users' => [1, 2, 3, 4]
        ]);

        $data2 = (new JsonData())->setDataFromArray([
            'users' => [1, 2.89, 3.14158, 4.0]
        ]);

        $schema = (new JsonSchema())->setSchema([
            'users' => ['type' => 'integer-list']
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testStringList(): void
    {
        $data = (new JsonData())->setDataFromArray([
            'users' => ['hello', 'i', 'am', 'testing']
        ]);

        $data2 = (new JsonData())->setDataFromArray([
            'users' => ['hello', 'i', 'am', 'testing', 'for', 'the', 2, 'time']
        ]);

        $schema = (new JsonSchema())->setSchema([
            'users' => ['type' => 'string-list']
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testCharList(): void
    {
        $data = (new JsonData())->setDataFromArray([
            'users' => ['a', 'b', 'c', 'd']
        ]);

        $data2 = (new JsonData())->setDataFromArray([
            'users' => ['a', 'bc', 'd', 'e']
        ]);

        $schema = (new JsonSchema())->setSchema([
            'users' => ['type' => 'char-list']
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testBooleanList(): void
    {
        $data = (new JsonData())->setDataFromArray([
            'users' => [true, false, false, true]
        ]);

        $data2 = (new JsonData())->setDataFromArray([
            'users' => [true, false, 0, true]
        ]);

        $schema = (new JsonSchema())->setSchema([
            'users' => ['type' => 'boolean-list']
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testFloatList(): void
    {
        $data = (new JsonData())->setDataFromArray([
            'users' => [1.1, 2.2, 3.3, 4.4]
        ]);

        $data2 = (new JsonData())->setDataFromArray([
            'users' => [1, 2.2, 3.3, 4.4]
        ]);

        $schema = (new JsonSchema())->setSchema([
            'users' => ['type' => 'float-list']
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testNumericList(): void
    {
        $data = (new JsonData())->setDataFromArray([
            'users' => [1, 2.89, 3.14158, 4.0]
        ]);

        $data2 = (new JsonData())->setDataFromArray([
            'users' => ['a', 2.89, 3.14158, 4.0]
        ]);

        $schema = (new JsonSchema())->setSchema([
            'users' => ['type' => 'numeric-list']
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     * Assertion should fail because the `random-list` type simply does not exist
     */
    public function testWrongTypeList(): void
    {
        $data = (new JsonData())->setDataFromArray([
            'users' => [1, 2.89, 3.14158, 4.0]
        ]);

        $schema = (new JsonSchema())->setSchema([
            'users' => ['type' => 'random-list']
        ]);

        $this->assertFalse($schema->validate($data));
    }
}