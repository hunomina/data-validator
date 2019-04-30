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
        $data = (new JsonData())->setDataAsArray([
            'users' => [1, 2, 3, 4]
        ]);

        $schema = (new JsonSchema())->setSchema([
            'users' => ['type' => 'integer-list']
        ]);

        $this->assertTrue($schema->validate($data));
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testStringList(): void
    {
        $data = (new JsonData())->setDataAsArray([
            'users' => ['hello', 'i', 'am', 'testing']
        ]);

        $schema = (new JsonSchema())->setSchema([
            'users' => ['type' => 'string-list']
        ]);

        $this->assertTrue($schema->validate($data));
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testCharList(): void
    {
        $data = (new JsonData())->setDataAsArray([
            'users' => ['a', 'b', 'c', 'd']
        ]);

        $schema = (new JsonSchema())->setSchema([
            'users' => ['type' => 'char-list']
        ]);

        $this->assertTrue($schema->validate($data));
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testBooleanList(): void
    {
        $data = (new JsonData())->setDataAsArray([
            'users' => [true, false, false, true]
        ]);

        $schema = (new JsonSchema())->setSchema([
            'users' => ['type' => 'boolean-list']
        ]);

        $this->assertTrue($schema->validate($data));
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testFloatList(): void
    {
        $data = (new JsonData())->setDataAsArray([
            'users' => [1.1, 2.2, 3.3, 4.4]
        ]);

        $schema = (new JsonSchema())->setSchema([
            'users' => ['type' => 'float-list']
        ]);

        $this->assertTrue($schema->validate($data));
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testNumericList(): void
    {
        $data = (new JsonData())->setDataAsArray([
            'users' => [1, 2.89, 3.14158, 4.0]
        ]);

        $schema = (new JsonSchema())->setSchema([
            'users' => ['type' => 'numeric-list']
        ]);

        $this->assertTrue($schema->validate($data));
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testWrongTypeList(): void
    {
        $data = (new JsonData())->setDataAsArray([
            'users' => [1, 2.89, 3.14158, 4.0]
        ]);

        $schema = (new JsonSchema())->setSchema([
            'users' => ['type' => 'random-list']
        ]);

        $this->assertFalse($schema->validate($data));
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testWrongList(): void
    {
        $data = (new JsonData())->setDataAsArray([
            'users' => [1, 2.89, 3.14158, 4.0]
        ]);

        $schema = (new JsonSchema())->setSchema([
            'users' => ['type' => 'int-list']
        ]);

        $this->assertFalse($schema->validate($data));
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testSecondWrongList(): void
    {
        $data = (new JsonData())->setDataAsArray([
            'users' => ['a', 'bc', 'd', 'e']
        ]);

        $schema = (new JsonSchema())->setSchema([
            'users' => ['type' => 'char-list']
        ]);

        $this->assertFalse($schema->validate($data));
    }
}