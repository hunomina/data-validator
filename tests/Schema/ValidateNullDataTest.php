<?php

namespace hunomina\Validator\Json\Test\Schema;

use hunomina\Validator\Json\Data\JsonData;
use hunomina\Validator\Json\Exception\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Schema\JsonSchema;
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