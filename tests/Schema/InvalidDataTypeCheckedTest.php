<?php

namespace hunomina\Validator\Json\Test\Schema;

use hunomina\Validator\Json\Data\DataType;
use hunomina\Validator\Json\Exception\Json\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\Json\InvalidSchemaException;
use hunomina\Validator\Json\Schema\Json\JsonSchema;
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