<?php

namespace hunomina\Validator\Json\Test\Schema\Json;

use hunomina\Validator\Json\Data\Json\JsonData;
use hunomina\Validator\Json\Exception\Json\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\Json\InvalidSchemaException;
use hunomina\Validator\Json\Schema\Json\JsonSchema;
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