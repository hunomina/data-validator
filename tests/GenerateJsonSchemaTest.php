<?php

namespace hunomina\Validator\Json\Test;

use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Schema\JsonSchema;
use PHPUnit\Framework\TestCase;

class GenerateJsonSchemaTest extends TestCase
{
    /**
     * @throws InvalidSchemaException
     */
    public function testGenerateOneLevelSchema(): void
    {
        $s = [
            'success' => ['type' => 'bool'],
            'error' => ['type' => 'string', 'null' => true],
            'code' => ['type' => 'int', 'null' => false, 'optional' => true]
        ];

        /** @var JsonSchema $schema */
        $schema = (new JsonSchema())->setSchema($s);
        $this->assertCount(3, $schema->getRules());
        $this->assertCount(0, $schema->getChildren());
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testGenerateSchemaWithList(): void
    {
        $s = [
            'success' => ['type' => 'bool'],
            'error' => ['type' => 'string', 'null' => true],
            'users' => ['type' => 'list', 'null' => false, 'optional' => false, 'schema' => [
                'name' => ['type' => 'string', 'null' => false, 'optional' => false],
                'age' => ['type' => 'int', 'null' => false, 'optional' => true],
                'birthplace' => ['type' => 'string', 'null' => true, 'optional' => true],
            ]]
        ];

        /** @var JsonSchema $schema */
        $schema = (new JsonSchema())->setSchema($s);
        $this->assertCount(2, $schema->getRules());
        $this->assertCount(1, $schema->getChildren());
        $this->assertCount(3, $schema->getChildren()['users']->getRules());
        $this->assertCount(0, $schema->getChildren()['users']->getChildren());
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testGenerateSchemaWithObject(): void
    {
        $s = [
            'success' => ['type' => 'bool'],
            'error' => ['type' => 'string', 'null' => true],
            'user' => ['type' => 'object', 'null' => false, 'optional' => false, 'schema' => [
                'name' => ['type' => 'string', 'null' => false, 'optional' => false],
                'age' => ['type' => 'int', 'null' => false, 'optional' => true],
                'birthplace' => ['type' => 'string', 'null' => true, 'optional' => true],
            ]]
        ];

        /** @var JsonSchema $schema */
        $schema = (new JsonSchema())->setSchema($s);
        $this->assertCount(2, $schema->getRules());
        $this->assertCount(1, $schema->getChildren());
        $this->assertCount(3, $schema->getChildren()['user']->getRules());
        $this->assertCount(0, $schema->getChildren()['user']->getChildren());
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testGenerateThirdLevelSchema(): void
    {
        $s = [
            'success' => ['type' => 'bool'],
            'error' => ['type' => 'string', 'null' => true],
            'users' => ['type' => 'list', 'null' => false, 'optional' => false, 'schema' => [
                'name' => ['type' => 'string', 'null' => false, 'optional' => false],
                'age' => ['type' => 'object', 'null' => false, 'optional' => true, 'schema' => [
                    'day' => ['type' => 'string', 'null' => false, 'optional' => false],
                    'hour' => ['type' => 'string', 'null' => false, 'optional' => false]
                ]]
            ]]
        ];

        /** @var JsonSchema $schema */
        $schema = (new JsonSchema())->setSchema($s);

        // first level
        $this->assertCount(2, $schema->getRules());
        $this->assertCount(1, $schema->getChildren());

        // second level
        $this->assertCount(1, $schema->getChildren()['users']->getRules());
        $this->assertCount(1, $schema->getChildren()['users']->getChildren());

        // third level
        $this->assertCount(2, $schema->getChildren()['users']->getChildren()['age']->getRules());
        $this->assertCount(0, $schema->getChildren()['users']->getChildren()['age']->getChildren());
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testErrorRuleWithNoType(): void
    {
        $s = [
            'success' => [],
        ];
        $this->expectException(InvalidSchemaException::class);
        (new JsonSchema())->setSchema($s);
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testErrorListWithNoSchema(): void
    {
        $s = [
            'user' => ['type' => 'object', 'null' => false, 'optional' => false]
        ];

        $this->expectException(InvalidSchemaException::class);
        (new JsonSchema())->setSchema($s);
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testErrorListWithInvalidSubSchema(): void
    {
        $s = [
            'user' => ['type' => 'object', 'null' => false, 'optional' => false, 'schema' => 'schema must be an array']
        ];

        $this->expectException(InvalidSchemaException::class);
        (new JsonSchema())->setSchema($s);
    }
}