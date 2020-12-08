<?php

namespace hunomina\DataValidator\Test\Data\Json;

use hunomina\DataValidator\Data\Json\JsonData;
use hunomina\DataValidator\Exception\Json\InvalidDataException;
use PHPUnit\Framework\TestCase;

/**
 * Class DataFormattingTest
 * @package hunomina\DataValidator\Test\Data\Json
 * @covers \hunomina\DataValidator\Data\Json\JsonData
 */
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

        (new JsonData())->format(null);
    }

    /**
     * @throws InvalidDataException
     */
    public function testThrowOnInvalidJsonParameterPassedToFormat(): void
    {
        $this->expectException(InvalidDataException::class);
        $this->expectExceptionCode(InvalidDataException::INVALID_JSON_DATA);

        (new JsonData())->format('\\');
    }

    /**
     * @throws InvalidDataException
     */
    public function testThrowOnNonJsonArrayParameterPassedToFormat(): void
    {
        $this->expectException(InvalidDataException::class);
        $this->expectExceptionCode(InvalidDataException::INVALID_JSON_DATA);

        (new JsonData())->format('a');
    }

    /**
     * @throws InvalidDataException
     */
    public function testFormatWithValidJsonButNotAnArray(): void
    {
        $this->expectException(InvalidDataException::class);
        $this->expectExceptionCode(InvalidDataException::INVALID_JSON_DATA);

        (new JsonData())->format('null');
    }

    /**
     * @throws InvalidDataException
     * Ensure that passing a valid json to format() works
     */
    public function testFormatWithValidJson(): void
    {
        $data = new JsonData();
        self::assertIsArray($data->format('[null]'));
    }

    /**
     * @throws InvalidDataException
     * Ensure that passing a valid json to setData() (or constructor) works
     */
    public function testSetDataWithValidJson(): void
    {
        $data = new JsonData();
        self::assertInstanceOf(JsonData::class, $data->setData('[null]'));
    }
}