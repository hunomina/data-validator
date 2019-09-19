<?php

namespace hunomina\Validator\Json\Test\Rule;

use hunomina\Validator\Json\Data\JsonData;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use PHPUnit\Framework\TestCase;

class EnumCheckTest extends TestCase
{
    /**
     * @throws InvalidSchemaException
     */
    public function testEnumForString(): void
    {
        $data = (new JsonData())->setDataFromArray([
            'gender' => 'female'
        ]);

        $data2 = (new JsonData())->setDataFromArray([
            'gender' => 'fish'
        ]);

        $schema = (new JsonSchema())->setSchema([
            'gender' => ['type' => JsonRule::STRING_TYPE, 'enum' => ['male', 'female']]
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testEnumForInteger(): void
    {
        $data = (new JsonData())->setDataFromArray([
            'feet' => 2
        ]);

        $data2 = (new JsonData())->setDataFromArray([
            'feet' => 5
        ]);

        $schema = (new JsonSchema())->setSchema([
            'feet' => ['type' => JsonRule::INTEGER_TYPE, 'enum' => [2, 3, 4]]
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testEnumForFloat(): void
    {
        $data = (new JsonData())->setDataFromArray([
            'feet' => 2.0
        ]);

        $data2 = (new JsonData())->setDataFromArray([
            'feet' => 5.0
        ]);

        $schema = (new JsonSchema())->setSchema([
            'feet' => ['type' => JsonRule::FLOAT_TYPE, 'enum' => [2.0, 3.0, 4.0]]
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testEnumForCharacter(): void
    {
        $data = (new JsonData())->setDataFromArray([
            'blood_type' => 'A'
        ]);

        $data2 = (new JsonData())->setDataFromArray([
            'blood_type' => 'C'
        ]);

        $schema = (new JsonSchema())->setSchema([
            'blood_type' => ['type' => JsonRule::CHAR_TYPE, 'enum' => ['A', 'B', 'O']]
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testEnumForTypedArray(): void
    {
        $data = (new JsonData())->setDataFromArray([
            'blood_types' => ['A', 'B', 'O']
        ]);

        $data2 = (new JsonData())->setDataFromArray([
            'blood_types' => ['A', 'B', 'C']
        ]);

        $schema = (new JsonSchema())->setSchema([
            'blood_types' => ['type' => JsonRule::CHAR_LIST_TYPE, 'enum' => ['A', 'B', 'O']]
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }
}