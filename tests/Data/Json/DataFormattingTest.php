<?php

namespace hunomina\DataValidator\Test\Data\Json;

use hunomina\DataValidator\Data\Json\JsonData;
use hunomina\DataValidator\Exception\Json\InvalidDataException;
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

    /**
     * @throws InvalidDataException
     */
    public function testFormatWithValidJsonButNotAnArray(): void
    {
        $this->expectException(InvalidDataException::class);
        $this->expectExceptionCode(InvalidDataException::INVALID_JSON_DATA);

        $schema = new JsonData();
        $schema->format('null');
    }

    /**
     * @throws InvalidDataException
     * Ensure that passing a valid json to format() works
     */
    public function testFormatWithValidJson(): void
    {
        $schema = new JsonData();
        $this->assertIsArray($schema->format('[null]'));
    }

    /**
     * @throws InvalidDataException
     * Ensure that passing a valid json to setData() (or constructor) works
     */
    public function testSetDataWithValidJson(): void
    {
        $schema = new JsonData();
        $this->assertInstanceOf(JsonData::class, $schema->setData('[null]'));
    }
}