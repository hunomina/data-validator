<?php

namespace hunomina\DataValidator\Test\Rule\Json\Check;

use hunomina\DataValidator\Exception\Json\InvalidDataException;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Rule\Json\StringRule;
use PHPUnit\Framework\TestCase;

/**
 * Class DateFormatCheckTest
 * @package hunomina\DataValidator\Test\Rule\Json\Traits
 */
class DateFormatCheckTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param JsonRule $rule
     * @param $data
     * @param bool $success
     * @throws InvalidDataException
     */
    public function testDateFormatCheck(JsonRule $rule, $data, bool $success): void
    {
        if (!$success) {
            $this->expectException(InvalidDataException::class);
            $this->expectExceptionCode(InvalidDataException::INVALID_DATE_FORMAT);

            $rule->validate($data);
        } else {
            self::assertTrue($rule->validate($data));
        }
    }

    /**
     * @return array[]
     */
    public function getTestableData(): array
    {
        return [
            self::StringDateformatCheck(),
            self::StringDateformatCheckFail()
        ];
    }

    /**
     * @return array
     * @covers StringRule
     */
    private static function StringDateformatCheck(): array
    {
        $rule = new StringRule();
        $rule->setDateFormat('Y-m-d');
        return [$rule, '1997-01-01', true];
    }

    /**
     * @return array
     * @covers StringRule
     */
    private static function StringDateformatCheckFail(): array
    {
        $rule = new StringRule();
        $rule->setDateFormat('Y-m-d');
        return [$rule, 'invalid-date-format', false];
    }
}