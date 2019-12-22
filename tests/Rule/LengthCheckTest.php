<?php

namespace hunomina\Validator\Json\Test\Rule;

use hunomina\Validator\Json\Data\JsonData;
use hunomina\Validator\Json\Exception\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use PHPUnit\Framework\TestCase;

class LengthCheckTest extends TestCase
{
    /**
     * @throws InvalidSchemaException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    public function testStringLengthCheck(): void
    {
        $data = new JsonData([
            'username' => 'test'
        ]);

        $data2 = new JsonData([
            'username' => 'test2'
        ]);

        $schema = new JsonSchema([
            'username' => ['type' => JsonRule::STRING_TYPE, 'length' => 4]
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    public function testTypedListLengthCheck(): void
    {
        $data = new JsonData([
            'users' => [1, 2, 3, 4]
        ]);

        $data2 = new JsonData([
            'users' => [1, 2, 3, 4, 5]
        ]);

        $schema = new JsonSchema([
            'users' => ['type' => JsonRule::INTEGER_LIST_TYPE, 'length' => 4]
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    public function testMinInteger(): void
    {
        $data = new JsonData([
            'integer' => 2
        ]);

        $data2 = new JsonData([
            'integer' => 4
        ]);

        $schema = new JsonSchema([
            'integer' => ['type' => JsonRule::INTEGER_TYPE, 'min' => 3]
        ]);

        $this->assertFalse($schema->validate($data));
        $this->assertTrue($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    public function testMinFloat(): void
    {
        $data = new JsonData([
            'float' => 2.0
        ]);

        $data2 = new JsonData([
            'float' => 4.0
        ]);

        $schema = new JsonSchema([
            'float' => ['type' => JsonRule::FLOAT_TYPE, 'min' => 3.0]
        ]);

        $this->assertFalse($schema->validate($data));
        $this->assertTrue($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    public function testMinNumber(): void
    {
        $data = new JsonData([
            'number' => 2.0
        ]);

        $data2 = new JsonData([
            'number' => 4
        ]);

        $schema = new JsonSchema([
            'number' => ['type' => JsonRule::NUMERIC_TYPE, 'min' => 3]
        ]);

        $this->assertFalse($schema->validate($data));
        $this->assertTrue($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    public function testMaxInteger(): void
    {
        $data = new JsonData([
            'integer' => 2
        ]);

        $data2 = new JsonData([
            'integer' => 4
        ]);

        $schema = new JsonSchema([
            'integer' => ['type' => JsonRule::INTEGER_TYPE, 'max' => 3]
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    public function testMaxFloat(): void
    {
        $data = new JsonData([
            'float' => 2.0
        ]);

        $data2 = new JsonData([
            'float' => 4.0
        ]);

        $schema = new JsonSchema([
            'float' => ['type' => JsonRule::FLOAT_TYPE, 'max' => 3.0]
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    public function testMaxNumber(): void
    {
        $data = new JsonData([
            'number' => 2
        ]);

        $data2 = new JsonData([
            'number' => 4.0
        ]);

        $schema = new JsonSchema([
            'number' => ['type' => JsonRule::NUMERIC_TYPE, 'max' => 3]
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidDataTypeException
     */
    public function testIntervalInteger(): void
    {
        $data = new JsonData([
            'integer' => 1
        ]);

        $data2 = new JsonData([
            'integer' => 5
        ]);

        $data3 = new JsonData([
            'integer' => 3
        ]);

        $schema = new JsonSchema([
            'integer' => ['type' => JsonRule::INTEGER_TYPE, 'min' => 2, 'max' => 4]
        ]);

        $this->assertFalse($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
        $this->assertTrue($schema->validate($data3));
    }

    /**
     * @throws InvalidSchemaException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidDataTypeException
     */
    public function testIntervalFloat(): void
    {
        $data = new JsonData([
            'float' => 1.0
        ]);

        $data2 = new JsonData([
            'float' => 5.0
        ]);

        $data3 = new JsonData([
            'float' => 3.0
        ]);

        $schema = new JsonSchema([
            'float' => ['type' => JsonRule::FLOAT_TYPE, 'min' => 2.0, 'max' => 4]
        ]);

        $this->assertFalse($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
        $this->assertTrue($schema->validate($data3));
    }

    /**
     * @throws InvalidSchemaException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidDataTypeException
     */
    public function testIntervalNumber(): void
    {
        $data = new JsonData([
            'number' => 1.0
        ]);

        $data2 = new JsonData([
            'number' => 5
        ]);

        $data3 = new JsonData([
            'number' => 3.0
        ]);

        $schema = new JsonSchema([
            'number' => ['type' => JsonRule::NUMERIC_TYPE, 'min' => 2.0, 'max' => 4]
        ]);

        $this->assertFalse($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
        $this->assertTrue($schema->validate($data3));
    }

    /**
     * @throws InvalidSchemaException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * Here we're checking the list size not the values
     */
    public function testMinSizeTypedList(): void
    {
        $data = new JsonData([
            'list' => [1, 2, 3]
        ]);

        $data2 = new JsonData([
            'list' => [1]
        ]);

        $schema = new JsonSchema([
            'list' => ['type' => JsonRule::INTEGER_LIST_TYPE, 'min' => 2]
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    public function testMaxSizeTypedList(): void
    {
        $data = new JsonData([
            'list' => [1, 2, 3]
        ]);

        $data2 = new JsonData([
            'list' => [1]
        ]);

        $schema = new JsonSchema([
            'list' => ['type' => JsonRule::INTEGER_LIST_TYPE, 'max' => 2]
        ]);

        $this->assertFalse($schema->validate($data));
        $this->assertTrue($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidDataTypeException
     */
    public function testIntervalSizeTypedList(): void
    {
        $data = new JsonData([
            'list' => [1]
        ]);

        $data2 = new JsonData([
            'list' => [1, 2, 3, 4, 5]
        ]);

        $data3 = new JsonData([
            'list' => [1, 2, 3]
        ]);

        $schema = new JsonSchema([
            'list' => ['type' => JsonRule::INTEGER_LIST_TYPE, 'min' => 2, 'max' => 4]
        ]);

        $this->assertFalse($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
        $this->assertTrue($schema->validate($data3));
    }
}