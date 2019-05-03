<?php

namespace hunomina\Validator\Json\Test;

use hunomina\Validator\Json\Data\JsonData;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Schema\JsonSchema;
use PHPUnit\Framework\TestCase;

class PatternCheckTest extends TestCase
{
    /**
     * @throws InvalidSchemaException
     */
    public function testInvalidTypeForPattern(): void
    {
        $this->expectException(InvalidSchemaException::class);

        (new JsonSchema())->setSchema([
            'age' => ['type' => 'int', 'pattern' => '/^nop$/']
        ]);
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testPatternOnString(): void
    {
        $data = (new JsonData())->setDataFromArray([
            'name' => 'test'
        ]);

        $schema = (new JsonSchema())->setSchema([
            'name' => ['type' => 'string', 'pattern' => '/^[a-z]+$/']
        ]);

        $this->assertTrue($schema->validate($data));
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testWrongPatternOnString(): void
    {
        $data = (new JsonData())->setDataFromArray([
            'name' => 'test2'
        ]);

        $schema = (new JsonSchema())->setSchema([
            'name' => ['type' => 'string', 'pattern' => '/^[a-z]+$/']
        ]);

        $this->assertFalse($schema->validate($data));
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testPatternOnChar(): void
    {
        $data = (new JsonData())->setDataFromArray([
            'blood_type' => 'o'
        ]);

        $schema = (new JsonSchema())->setSchema([
            'blood_type' => ['type' => 'char', 'pattern' => '/^[abo]$/']
        ]);

        $this->assertTrue($schema->validate($data));
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testWrongPatternOnChar(): void
    {
        $data = (new JsonData())->setDataFromArray([
            'blood_type' => 'o'
        ]);

        $schema = (new JsonSchema())->setSchema([
            'blood_type' => ['type' => 'char', 'pattern' => '/^[xyz]$/']
        ]);

        $this->assertFalse($schema->validate($data));
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testPatternOnStringList(): void
    {
        $data = (new JsonData())->setDataFromArray([
            'list' => [
                'hello',
                'love',
                'test'
            ]
        ]);

        $schema = (new JsonSchema())->setSchema([
            'list' => ['type' => 'string-list', 'pattern' => '/^[a-zA-Z]+$/']
        ]);

        $this->assertTrue($schema->validate($data));
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testWrongPatternOnStringList(): void
    {
        $data = (new JsonData())->setDataFromArray([
            'list' => [
                'won\'t',
                'work',
                'sorry'
            ]
        ]);

        $schema = (new JsonSchema())->setSchema([
            'list' => ['type' => 'string-list', 'pattern' => '/^[a-zA-Z]+$/']
        ]);

        $this->assertFalse($schema->validate($data));
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testPatternOnCharacterList(): void
    {
        $data = (new JsonData())->setDataFromArray([
            'blood_type_list' => [
                'a',
                'b',
                'o'
            ]
        ]);

        $schema = (new JsonSchema())->setSchema([
            'blood_type_list' => ['type' => 'char-list', 'pattern' => '/^[abo]$/']
        ]);

        $this->assertTrue($schema->validate($data));
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testWrongPatternOnCharacterList(): void
    {
        $data = (new JsonData())->setDataFromArray([
            'blood_type_list' => [
                'a',
                'b',
                'o'
            ]
        ]);

        $schema = (new JsonSchema())->setSchema([
            'blood_type_list' => ['type' => 'char-list', 'pattern' => '/^[xyz]$/']
        ]);

        $this->assertFalse($schema->validate($data));
    }
}