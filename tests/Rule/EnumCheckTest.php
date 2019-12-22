<?php

namespace hunomina\Validator\Json\Test\Rule;

use hunomina\Validator\Json\Data\JsonData;
use hunomina\Validator\Json\Exception\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use PHPUnit\Framework\TestCase;

class EnumCheckTest extends TestCase
{
    /**
     * @throws InvalidSchemaException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    public function testEnumForString(): void
    {
        $data = new JsonData([
            'gender' => 'female'
        ]);

        $data2 = new JsonData([
            'gender' => 'fish'
        ]);

        $schema = new JsonSchema([
            'gender' => ['type' => JsonRule::STRING_TYPE, 'enum' => ['male', 'female']]
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    public function testEnumForInteger(): void
    {
        $data = new JsonData([
            'feet' => 2
        ]);

        $data2 = new JsonData([
            'feet' => 5
        ]);

        $schema = new JsonSchema([
            'feet' => ['type' => JsonRule::INTEGER_TYPE, 'enum' => [2, 3, 4]]
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    public function testEnumForFloat(): void
    {
        $data = new JsonData([
            'feet' => 2.0
        ]);

        $data2 = new JsonData([
            'feet' => 5.0
        ]);

        $schema = new JsonSchema([
            'feet' => ['type' => JsonRule::FLOAT_TYPE, 'enum' => [2.0, 3.0, 4.0]]
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    public function testEnumForCharacter(): void
    {
        $data = new JsonData([
            'blood_type' => 'A'
        ]);

        $data2 = new JsonData([
            'blood_type' => 'C'
        ]);

        $schema = new JsonSchema([
            'blood_type' => ['type' => JsonRule::CHAR_TYPE, 'enum' => ['A', 'B', 'O']]
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    public function testEnumForTypedArray(): void
    {
        $data = new JsonData([
            'blood_types' => ['A', 'B', 'O']
        ]);

        $data2 = new JsonData([
            'blood_types' => ['A', 'B', 'C']
        ]);

        $schema = new JsonSchema([
            'blood_types' => ['type' => JsonRule::CHAR_LIST_TYPE, 'enum' => ['A', 'B', 'O']]
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }
}