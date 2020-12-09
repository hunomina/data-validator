<?php

namespace hunomina\DataValidator\Test\Schema\Json;

use hunomina\DataValidator\Exception\Json\InvalidSchemaException;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use PHPUnit\Framework\TestCase;

/**
 * Class InvalidSchemaTypeTest
 * @package hunomina\DataValidator\Test\Schema\Json
 * @covers \hunomina\DataValidator\Schema\Json\JsonSchema
 */
class InvalidSchemaTypeTest extends TestCase
{
    public function testThrowOnInvalidSchemaType(): void
    {
        $this->expectException(InvalidSchemaException::class);
        $this->expectExceptionCode(InvalidSchemaException::INVALID_SCHEMA_TYPE);

        $schema = new JsonSchema();
        $schema->setType('invalid-type');
    }
}