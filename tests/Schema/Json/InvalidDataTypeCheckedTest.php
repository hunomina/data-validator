<?php

namespace hunomina\DataValidator\Test\Schema\Json;

use hunomina\DataValidator\Exception\InvalidDataTypeArgumentException;
use hunomina\DataValidator\Exception\Json\InvalidDataException;
use hunomina\DataValidator\Exception\Json\InvalidSchemaException;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use hunomina\DataValidator\Test\Data\TestDataType;
use PHPUnit\Framework\TestCase;

class InvalidDataTypeCheckedTest extends TestCase
{
    /**
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    public function testThrowOnInvalidDataTypeChecked(): void
    {
        $this->expectException(InvalidDataTypeArgumentException::class);

        $data = new TestDataType(); // not a JsonData
        $schema = new JsonSchema();

        $schema->validate($data);
    }
}