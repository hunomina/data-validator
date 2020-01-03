<?php

namespace hunomina\Validator\Json\Test\Schema;

use hunomina\Validator\Json\Exception\Json\InvalidSchemaException;
use hunomina\Validator\Json\Schema\Json\JsonSchema;
use PHPUnit\Framework\TestCase;

class InvalidSchemaTypeTest extends TestCase
{
    /**
     * @throws InvalidSchemaException
     */
    public function testThrowOnInvalidSchemaType(): void
    {
        $this->expectException(InvalidSchemaException::class);
        $this->expectExceptionCode(InvalidSchemaException::INVALID_SCHEMA_TYPE);

        $schema = new JsonSchema();
        $schema->setType('invalid-type');
    }
}