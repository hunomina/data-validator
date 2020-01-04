<?php

namespace hunomina\DataValidator\Test\Schema\Json;

use hunomina\DataValidator\Data\Json\JsonData;
use hunomina\DataValidator\Exception\Json\InvalidDataException;
use hunomina\DataValidator\Exception\InvalidDataTypeException;
use hunomina\DataValidator\Exception\Json\InvalidSchemaException;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use PHPUnit\Framework\TestCase;

class ValidateNullDataTest extends TestCase
{
    /**
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testValidateNullData(): void
    {
        $schema = new JsonSchema();
        $schema->setNullable(true);

        $this->assertTrue($schema->validate(new JsonData()));
    }

    /**
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowOnInvalidNullData(): void
    {
        $this->expectException(InvalidDataException::class);
        $this->expectExceptionCode(InvalidDataException::NULL_VALUE_NOT_ALLOWED);

        (new JsonSchema())->validate(new JsonData());
    }
}