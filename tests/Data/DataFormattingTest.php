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
    public function testThrowOnIntegerParameterPassedToTheConstructor(): void
    {
        $this->expectException(InvalidDataException::class);
        $this->expectExceptionCode(InvalidDataException::INVALID_DATA_TYPE);

        new JsonData(1);
    }

    /**
     * @throws InvalidDataException
     */
    public function testThrowOnFloatParameterPassedToTheConstructor(): void
    {
        $this->expectException(InvalidDataException::class);
        $this->expectExceptionCode(InvalidDataException::INVALID_DATA_TYPE);

        new JsonData(1.0);
    }

    /**
     * @throws InvalidDataException
     */
    public function testThrowOnBooleanParameterPassedToTheConstructor(): void
    {
        $this->expectException(InvalidDataException::class);
        $this->expectExceptionCode(InvalidDataException::INVALID_DATA_TYPE);

        new JsonData(true);
    }

    /**
     * @throws InvalidDataException
     */
    public function testThrowOnNonStringParameterPassedToFormat(): void
    {
        $this->expectException(InvalidDataException::class);
        $this->expectExceptionCode(InvalidDataException::INVALID_DATA_TYPE);

        $schema = new JsonData();
        $schema->format(null);
    }

    /**
     * @throws InvalidDataException
     */
    public function testThrowOnInvalidJsonParameterPassedToFormat(): void
    {
        $this->expectException(InvalidDataException::class);
        $this->expectExceptionCode(InvalidDataException::INVALID_JSON_DATA);

        $schema = new JsonData();
        $schema->format('\\');
    }

    /**
     * @throws InvalidDataException
     */
    public function testThrowOnNonJsonArrayParameterPassedToFormat(): void
    {
        $this->expectException(InvalidDataException::class);
        $this->expectExceptionCode(InvalidDataException::INVALID_JSON_DATA);

        $schema = new JsonData();
        $schema->format('a');
    }
}