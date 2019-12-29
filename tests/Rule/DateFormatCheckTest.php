<?php

namespace hunomina\Validator\Json\Test\Rule;

use hunomina\Validator\Json\Data\JsonData;
use hunomina\Validator\Json\Exception\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use PHPUnit\Framework\TestCase;

class DateFormatCheckTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param JsonData $data
     * @param JsonSchema $schema
     * @param bool $success
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    public function testDateFormatCheck(JsonData $data, JsonSchema $schema, bool $success): void
    {
        if (!$success) {
            $this->expectException(InvalidDataException::class);
            $this->expectExceptionCode(InvalidDataException::INVALID_DATE_FORMAT);

            $schema->validate($data);
        } else {
            $this->assertTrue($schema->validate($data));
        }
    }

    /**
     * @return array
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    public function getTestableData(): array
    {
        return [
            self::YmdFormat(),
            self::YmdFormatFail(),
            self::TimestampFormat(),
            self::TimestampFormatFail(),
            self::AdvancedFormatCheck(),
            self::AdvancedFormatCheckFail(),
            self::ExistingDateCheck(),
            self::NonExistingDateCheck()
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function YmdFormat(): array
    {
        return [
            new JsonData([
                'birthday' => '2000-01-01'
            ]),
            new JsonSchema([
                'birthday' => ['type' => JsonRule::STRING_TYPE, 'date-format' => 'Y-m-d']
            ]),
            true
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function YmdFormatFail(): array
    {
        return [
            new JsonData([
                'birthday' => 'January the first in 2000'
            ]),
            new JsonSchema([
                'birthday' => ['type' => JsonRule::STRING_TYPE, 'date-format' => 'Y-m-d']
            ]),
            false
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function TimestampFormat(): array
    {
        return [
            new JsonData([
                'birthday' => '1234567890'
            ]),
            new JsonSchema([
                'birthday' => ['type' => JsonRule::STRING_TYPE, 'date-format' => 'U']
            ]),
            true
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function TimestampFormatFail(): array
    {
        return [
            new JsonData([
                'birthday' => '2000-01-01'
            ]),
            new JsonSchema([
                'birthday' => ['type' => JsonRule::STRING_TYPE, 'date-format' => 'U']
            ]),
            false
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     * @see https://www.w3.org/TR/NOTE-datetime
     */
    private static function AdvancedFormatCheck(): array
    {
        return [
            new JsonData([
                'birthday' => 'Sat 01 January 2000'
            ]),
            new JsonSchema([
                'birthday' => ['type' => JsonRule::STRING_TYPE, 'date-format' => 'D d F Y']
            ]),
            true
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function AdvancedFormatCheckFail(): array
    {
        return [
            new JsonData([
                'birthday' => '2000-01-01'
            ]),
            new JsonSchema([
                'birthday' => ['type' => JsonRule::STRING_TYPE, 'date-format' => 'D d F Y']
            ]),
            false
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function ExistingDateCheck(): array
    {
        return [
            new JsonData([
                'birthday' => 'Sat 01 January 2000' // valid day
            ]),
            new JsonSchema([
                'birthday' => ['type' => JsonRule::STRING_TYPE, 'date-format' => 'D d F Y']
            ]),
            true
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function NonExistingDateCheck(): array
    {
        return [
            new JsonData([
                'birthday' => 'Mon 01 January 2000' // invalid day
            ]),
            new JsonSchema([
                'birthday' => ['type' => JsonRule::STRING_TYPE, 'date-format' => 'D d F Y']
            ]),
            false
        ];
    }
}