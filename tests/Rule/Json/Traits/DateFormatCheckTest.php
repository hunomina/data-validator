<?php

namespace hunomina\DataValidator\Test\Rule\Json\Traits;

use hunomina\DataValidator\Data\Json\JsonData;
use hunomina\DataValidator\Exception\Json\InvalidDataException;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use PHPUnit\Framework\TestCase;

class DateFormatCheckTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param JsonData $data
     * @param JsonSchema $schema
     * @param bool $success
     * @throws InvalidDataException
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