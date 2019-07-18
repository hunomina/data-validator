<?php

namespace hunomina\Validator\Json\Test;

use hunomina\Validator\Json\Data\JsonData;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use PHPUnit\Framework\TestCase;

class LengthCheckTest extends TestCase
{
    /**
     * @throws InvalidSchemaException
     */
    public function testStringLengthCheck(): void
    {
        $data = (new JsonData())->setDataFromArray([
            'username' => 'test'
        ]);

        $data2 = (new JsonData())->setDataFromArray([
            'username' => 'test2'
        ]);

        $schema = (new JsonSchema())->setSchema([
            'username' => ['type' => JsonRule::STRING_TYPE, 'length' => 4]
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testTypedListLengthCheck(): void
    {
        $data = (new JsonData())->setDataFromArray([
            'users' => [1, 2, 3, 4]
        ]);

        $data2 = (new JsonData())->setDataFromArray([
            'users' => [1, 2, 3, 4, 5]
        ]);

        $schema = (new JsonSchema())->setSchema([
            'users' => ['type' => JsonRule::INTEGER_LIST_TYPE, 'length' => 4]
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testMinInteger(): void
    {
        $data = (new JsonData())->setDataFromArray([
            'integer' => 2
        ]);

        $data2 = (new JsonData())->setDataFromArray([
            'integer' => 4
        ]);

        $schema = (new JsonSchema())->setSchema([
            'integer' => ['type' => JsonRule::INTEGER_TYPE, 'min' => 3]
        ]);

        $this->assertFalse($schema->validate($data));
        $this->assertTrue($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testMinFloat(): void
    {
        $data = (new JsonData())->setDataFromArray([
            'float' => 2.0
        ]);

        $data2 = (new JsonData())->setDataFromArray([
            'float' => 4.0
        ]);

        $schema = (new JsonSchema())->setSchema([
            'float' => ['type' => JsonRule::FLOAT_TYPE, 'min' => 3.0]
        ]);

        $this->assertFalse($schema->validate($data));
        $this->assertTrue($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testMinNumber(): void
    {
        $data = (new JsonData())->setDataFromArray([
            'number' => 2.0
        ]);

        $data2 = (new JsonData())->setDataFromArray([
            'number' => 4
        ]);

        $schema = (new JsonSchema())->setSchema([
            'number' => ['type' => JsonRule::NUMERIC_TYPE, 'min' => 3]
        ]);

        $this->assertFalse($schema->validate($data));
        $this->assertTrue($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testMaxInteger(): void
    {
        $data = (new JsonData())->setDataFromArray([
            'integer' => 2
        ]);

        $data2 = (new JsonData())->setDataFromArray([
            'integer' => 4
        ]);

        $schema = (new JsonSchema())->setSchema([
            'integer' => ['type' => JsonRule::INTEGER_TYPE, 'max' => 3]
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testMaxFloat(): void
    {
        $data = (new JsonData())->setDataFromArray([
            'float' => 2.0
        ]);

        $data2 = (new JsonData())->setDataFromArray([
            'float' => 4.0
        ]);

        $schema = (new JsonSchema())->setSchema([
            'float' => ['type' => JsonRule::FLOAT_TYPE, 'max' => 3.0]
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testMaxNumber(): void
    {
        $data = (new JsonData())->setDataFromArray([
            'number' => 2
        ]);

        $data2 = (new JsonData())->setDataFromArray([
            'number' => 4.0
        ]);

        $schema = (new JsonSchema())->setSchema([
            'number' => ['type' => JsonRule::NUMERIC_TYPE, 'max' => 3]
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testIntervalInteger(): void
    {
        $data = (new JsonData())->setDataFromArray([
            'integer' => 1
        ]);

        $data2 = (new JsonData())->setDataFromArray([
            'integer' => 5
        ]);

        $data3 = (new JsonData())->setDataFromArray([
            'integer' => 3
        ]);

        $schema = (new JsonSchema())->setSchema([
            'integer' => ['type' => JsonRule::INTEGER_TYPE, 'min' => 2, 'max' => 4]
        ]);

        $this->assertFalse($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
        $this->assertTrue($schema->validate($data3));
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testIntervalFloat(): void
    {
        $data = (new JsonData())->setDataFromArray([
            'float' => 1.0
        ]);

        $data2 = (new JsonData())->setDataFromArray([
            'float' => 5.0
        ]);

        $data3 = (new JsonData())->setDataFromArray([
            'float' => 3.0
        ]);

        $schema = (new JsonSchema())->setSchema([
            'float' => ['type' => JsonRule::FLOAT_TYPE, 'min' => 2.0, 'max' => 4]
        ]);

        $this->assertFalse($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
        $this->assertTrue($schema->validate($data3));
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testIntervalNumber(): void
    {
        $data = (new JsonData())->setDataFromArray([
            'number' => 1.0
        ]);

        $data2 = (new JsonData())->setDataFromArray([
            'number' => 5
        ]);

        $data3 = (new JsonData())->setDataFromArray([
            'number' => 3.0
        ]);

        $schema = (new JsonSchema())->setSchema([
            'number' => ['type' => JsonRule::NUMERIC_TYPE, 'min' => 2.0, 'max' => 4]
        ]);

        $this->assertFalse($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
        $this->assertTrue($schema->validate($data3));
    }

    /**
     * @throws InvalidSchemaException
     * Here we're checking the list size not the values
     */
    public function testMinSizeTypedList(): void
    {
        $data = (new JsonData())->setDataFromArray([
            'list' => [1, 2]
        ]);

        $data2 = (new JsonData())->setDataFromArray([
            'list' => []
        ]);

        $schema = (new JsonSchema())->setSchema([
            'list' => ['type' => JsonRule::INTEGER_LIST_TYPE, 'min' => 1]
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testMaxSizeTypedList(): void
    {
        $data = (new JsonData())->setDataFromArray([
            'list' => [1, 2]
        ]);

        $data2 = (new JsonData())->setDataFromArray([
            'list' => []
        ]);

        $schema = (new JsonSchema())->setSchema([
            'list' => ['type' => JsonRule::INTEGER_LIST_TYPE, 'max' => 1]
        ]);

        $this->assertFalse($schema->validate($data));
        $this->assertTrue($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testIntervalSizeTypedList(): void
    {
        $data = (new JsonData())->setDataFromArray([
            'list' => [1]
        ]);

        $data2 = (new JsonData())->setDataFromArray([
            'list' => [1, 2, 3, 4, 5]
        ]);

        $data3 = (new JsonData())->setDataFromArray([
            'list' => [1, 2, 3]
        ]);

        $schema = (new JsonSchema())->setSchema([
            'list' => ['type' => JsonRule::INTEGER_LIST_TYPE, 'min' => 2, 'max' => 4]
        ]);

        $this->assertFalse($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
        $this->assertTrue($schema->validate($data3));
    }
}