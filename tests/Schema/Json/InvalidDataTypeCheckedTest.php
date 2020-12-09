<?php

namespace hunomina\DataValidator\Test\Schema\Json;

use hunomina\DataValidator\Data\DataType;
use hunomina\DataValidator\Exception\InvalidDataTypeArgumentException;
use hunomina\DataValidator\Exception\Json\InvalidDataException;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use PHPUnit\Framework\TestCase;

/**
 * Class InvalidDataTypeCheckedTest
 * @package hunomina\DataValidator\Test\Schema\Json
 * @covers \hunomina\DataValidator\Schema\Json\JsonSchema
 */
class InvalidDataTypeCheckedTest extends TestCase
{
    /**
     * @throws InvalidDataException
     */
    public function testThrowOnInvalidDataTypeChecked(): void
    {
        $this->expectException(InvalidDataTypeArgumentException::class);

        $data = new class implements DataType {

            public function getData(): array
            {
                return [];
            }

            public function setData($data): DataType
            {
                return $this;
            }

            public function format($data): array
            {
                return [];
            }
        }; // not a JsonData
        $schema = new JsonSchema();

        $schema->validate($data);
    }
}