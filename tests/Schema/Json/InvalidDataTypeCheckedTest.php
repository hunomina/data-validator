<?php

namespace hunomina\DataValidator\Test\Schema\Json;

use hunomina\DataValidator\Data\DataType;
use hunomina\DataValidator\Exception\Json\InvalidDataException;
use hunomina\DataValidator\Exception\InvalidDataTypeException;
use hunomina\DataValidator\Exception\Json\InvalidSchemaException;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use PHPUnit\Framework\TestCase;

class InvalidDataTypeCheckedTest extends TestCase
{
    /**
     * @throws InvalidDataTypeException
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    public function testThrowOnInvalidDataTypeChecked(): void
    {
        $this->expectException(InvalidDataTypeException::class);
        $this->expectExceptionCode(InvalidDataTypeException::INVALID_DATA_TYPE_USED);

        $data = new InvalidDataType(); // not a JsonData
        $schema = new JsonSchema();

        $schema->validate($data);
    }
}

class InvalidDataType implements DataType
{
    public function getData()
    {
    }

    public function setData($data): DataType
    {
        return $this;
    }

    public function format($data)
    {
    }
}