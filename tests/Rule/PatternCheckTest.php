<?php

namespace hunomina\Validator\Json\Test\Rule;

use hunomina\Validator\Json\Data\JsonData;
use hunomina\Validator\Json\Exception\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use PHPUnit\Framework\TestCase;

class PatternCheckTest extends TestCase
{
    /**
     * @throws InvalidSchemaException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    public function testPatternOnString(): void
    {
        $data = new JsonData([
            'name' => 'test'
        ]);

        $data2 = new JsonData([
            'name' => 'test2'
        ]);

        $schema = new JsonSchema([
            'name' => ['type' => JsonRule::STRING_TYPE, 'pattern' => '/^[a-z]+$/']
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    public function testPatternOnChar(): void
    {
        $data = new JsonData([
            'blood_type' => 'o'
        ]);

        $data2 = new JsonData([
            'blood_type' => 'c'
        ]);

        $schema = new JsonSchema([
            'blood_type' => ['type' => JsonRule::CHAR_TYPE, 'pattern' => '/^[abo]$/']
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    public function testPatternOnStringList(): void
    {
        $data = new JsonData([
            'list' => [
                'hello',
                'love',
                'test'
            ]
        ]);

        $data2 = new JsonData([
            'list' => [
                'won\'t',
                'work',
                'sorry'
            ]
        ]);

        $schema = new JsonSchema([
            'list' => ['type' => JsonRule::STRING_LIST_TYPE, 'pattern' => '/^[a-zA-Z]+$/']
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    public function testPatternOnCharacterList(): void
    {
        $data = new JsonData([
            'blood_types' => [
                'a',
                'b',
                'o'
            ]
        ]);

        $data2 = new JsonData([
            'blood_types' => [
                'a',
                'b',
                'c'
            ]
        ]);

        $schema = new JsonSchema([
            'blood_types' => ['type' => JsonRule::CHAR_LIST_TYPE, 'pattern' => '/^[abo]$/']
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }
}