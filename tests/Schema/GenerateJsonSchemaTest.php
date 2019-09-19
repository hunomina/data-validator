<?php

namespace hunomina\Validator\Json\Test\Schema;

use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
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
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'code' => ['type' => JsonRule::INTEGER_TYPE, 'null' => false, 'optional' => true]
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
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'users' => ['type' => JsonRule::LIST_TYPE, 'null' => false, 'optional' => false, 'schema' => [
                'name' => ['type' => JsonRule::STRING_TYPE, 'null' => false, 'optional' => false],
                'age' => ['type' => JsonRule::INTEGER_TYPE, 'null' => false, 'optional' => true],
                'birthplace' => ['type' => JsonRule::STRING_TYPE, 'null' => true, 'optional' => true],
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
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'user' => ['type' => JsonRule::OBJECT_TYPE, 'null' => false, 'optional' => false, 'schema' => [
                'name' => ['type' => JsonRule::STRING_TYPE, 'null' => false, 'optional' => false],
                'age' => ['type' => JsonRule::INTEGER_TYPE, 'null' => false, 'optional' => true],
                'birthplace' => ['type' => JsonRule::STRING_TYPE, 'null' => true, 'optional' => true],
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
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'users' => ['type' => JsonRule::LIST_TYPE, 'null' => false, 'optional' => false, 'schema' => [
                'name' => ['type' => JsonRule::STRING_TYPE, 'null' => false, 'optional' => false],
                'age' => ['type' => JsonRule::OBJECT_TYPE, 'null' => false, 'optional' => true, 'schema' => [
                    'day' => ['type' => JsonRule::STRING_TYPE, 'null' => false, 'optional' => false],
                    'hour' => ['type' => JsonRule::STRING_TYPE, 'null' => false, 'optional' => false]
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
        $this->expectException(InvalidSchemaException::class);
        $this->expectExceptionCode(InvalidSchemaException::MISSING_TYPE);

        $s = [
            'success' => [],
        ];

        (new JsonSchema())->setSchema($s);
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testErrorListWithNoSchema(): void
    {
        $this->expectException(InvalidSchemaException::class);
        $this->expectExceptionCode(InvalidSchemaException::MISSING_SCHEMA);

        $s = [
            'user' => ['type' => JsonRule::OBJECT_TYPE, 'null' => false, 'optional' => false]
        ];

        (new JsonSchema())->setSchema($s);
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testErrorListWithInvalidSubSchema(): void
    {
        $this->expectException(InvalidSchemaException::class);
        $this->expectExceptionCode(InvalidSchemaException::INVALID_OBJECT_SCHEMA);

        $s = [
            'user' => ['type' => JsonRule::OBJECT_TYPE, 'null' => false, 'optional' => false, 'schema' => 'schema must be an array']
        ];

        (new JsonSchema())->setSchema($s);
    }
}