<?php

namespace hunomina\DataValidator\Test\Schema\Json;

use hunomina\DataValidator\Exception\Json\InvalidSchemaException;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Schema\Json\JsonSchema;
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