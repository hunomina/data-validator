<?php

namespace hunomina\Validator\Json\Test\Data;

use hunomina\Validator\Json\Data\JsonData;
use hunomina\Validator\Json\Exception\InvalidDataException;
use PHPUnit\Framework\TestCase;

class DataFormattingTest extends TestCase
{
    /**
     * @throws InvalidDataException
     */
    public function testThrowOnInvalidSetParameter(): void
    {
        $this->expectException(InvalidDataException::class);
        $this->expectExceptionCode(InvalidDataException::INVALID_DATA_TYPE);

        $data = new JsonData();
        $data->setData(1234567890);
    }
    
    /**
     * @throws InvalidDataException
     */
    public function testThrowOnInvalidFormatParameter(): void
    {
        $this->expectException(InvalidDataException::class);
        $this->expectExceptionCode(InvalidDataException::INVALID_DATA_TYPE);

        $data = new JsonData();
        $data->format(1234567890);
    }

    /**
     * @throws InvalidDataException
     */
    public function testThrowOnInvalidJsonStringParameter(): void
    {
        $this->expectException(InvalidDataException::class);
        $this->expectExceptionCode(InvalidDataException::INVALID_JSON_DATA);

        $data = new JsonData();
        $data->format('invalid json string');
    }
}