<?php

namespace hunomina\Validator\Json\Test\Schema;

use hunomina\Validator\Json\Exception\Json\InvalidSchemaException;
use hunomina\Validator\Json\Rule\Json\JsonRule;
use hunomina\Validator\Json\Schema\Json\JsonSchema;
use PHPUnit\Framework\TestCase;

class InvalidChildSchemaTest extends TestCase
{
    /**
     * @throws InvalidSchemaException
     */
    public function testThrowOnInvalidChildSchema(): void
    {
        $this->expectException(InvalidSchemaException::class);
        $this->expectExceptionCode(InvalidSchemaException::INVALID_CHILD_SCHEMA);

        new JsonSchema([
            'object' => ['type' => JsonRule::OBJECT_TYPE, 'schema' => 'invalid-schema']
        ]);
    }
}