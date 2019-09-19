<?php

namespace hunomina\Validator\Json\Test\Rule;

use hunomina\Validator\Json\Data\JsonData;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use PHPUnit\Framework\TestCase;

class PatternCheckTest extends TestCase
{
    /**
     * @throws InvalidSchemaException
     */
    public function testPatternOnString(): void
    {
        $data = (new JsonData())->setDataFromArray([
            'name' => 'test'
        ]);

        $data2 = (new JsonData())->setDataFromArray([
            'name' => 'test2'
        ]);

        $schema = (new JsonSchema())->setSchema([
            'name' => ['type' => JsonRule::STRING_TYPE, 'pattern' => '/^[a-z]+$/']
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testPatternOnChar(): void
    {
        $data = (new JsonData())->setDataFromArray([
            'blood_type' => 'o'
        ]);

        $data2 = (new JsonData())->setDataFromArray([
            'blood_type' => 'c'
        ]);

        $schema = (new JsonSchema())->setSchema([
            'blood_type' => ['type' => JsonRule::CHAR_TYPE, 'pattern' => '/^[abo]$/']
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
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

        $data2 = (new JsonData())->setDataFromArray([
            'list' => [
                'won\'t',
                'work',
                'sorry'
            ]
        ]);

        $schema = (new JsonSchema())->setSchema([
            'list' => ['type' => JsonRule::STRING_LIST_TYPE, 'pattern' => '/^[a-zA-Z]+$/']
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testPatternOnCharacterList(): void
    {
        $data = (new JsonData())->setDataFromArray([
            'blood_types' => [
                'a',
                'b',
                'o'
            ]
        ]);

        $data2 = (new JsonData())->setDataFromArray([
            'blood_types' => [
                'a',
                'b',
                'c'
            ]
        ]);

        $schema = (new JsonSchema())->setSchema([
            'blood_types' => ['type' => JsonRule::CHAR_LIST_TYPE, 'pattern' => '/^[abo]$/']
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }
}