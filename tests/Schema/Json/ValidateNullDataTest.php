<?php

namespace hunomina\DataValidator\Test\Schema\Json;

use hunomina\DataValidator\Data\Json\JsonData;
use hunomina\DataValidator\Exception\Json\InvalidDataException;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use PHPUnit\Framework\TestCase;

class ValidateNullDataTest extends TestCase
{
    /**
     * @throws InvalidDataException
     */
    public function testValidateNullData(): void
    {
        $schema = new JsonSchema();
        $schema->setNullable(true);

        self::assertTrue($schema->validate(new JsonData()));
    }

    /**
     * @throws InvalidDataException
     */
    public function testThrowOnInvalidNullData(): void
    {
        $this->expectException(InvalidDataException::class);
        $this->expectExceptionCode(InvalidDataException::NULL_VALUE_NOT_ALLOWED);

        (new JsonSchema())->validate(new JsonData());
    }
}