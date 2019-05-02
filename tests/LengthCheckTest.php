<?php

namespace hunomina\Validator\Json\Test;

use hunomina\Validator\Json\Data\JsonData;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Schema\JsonSchema;
use PHPUnit\Framework\TestCase;

class LengthCheckTest extends TestCase
{
    /**
     * @throws InvalidSchemaException
     */
    public function testBasicStringSchema(): void
    {
        $data = (new JsonData())->setDataAsArray([
            'username' => 'test'
        ]);

        $schema = (new JsonSchema())->setSchema([
            'username' => ['type' => 'string']
        ]);

        $this->assertTrue($schema->validate($data));
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testStringLengthCheck(): void
    {
        $data = (new JsonData())->setDataAsArray([
            'username' => 'test'
        ]);

        $schema = (new JsonSchema())->setSchema([
            'username' => ['type' => 'string', 'length' => 4]
        ]);

        $this->assertTrue($schema->validate($data));
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testStringFailLengthCheck(): void
    {
        $data = (new JsonData())->setDataAsArray([
            'username' => 'test'
        ]);

        $schema = (new JsonSchema())->setSchema([
            'username' => ['type' => 'string', 'length' => 5]
        ]);

        $this->assertFalse($schema->validate($data));
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testTypedListLengthCheck(): void
    {
        $data = (new JsonData())->setDataAsArray([
            'users' => [1, 2, 3, 4]
        ]);

        $schema = (new JsonSchema())->setSchema([
            'users' => ['type' => 'integer-list', 'length' => 4]
        ]);

        $this->assertTrue($schema->validate($data));
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testTypedListFailLengthCheck(): void
    {
        $data = (new JsonData())->setDataAsArray([
            'users' => [1, 2, 3, 4]
        ]);

        $schema = (new JsonSchema())->setSchema([
            'users' => ['type' => 'integer-list', 'length' => 5]
        ]);

        $this->assertFalse($schema->validate($data));
    }
}