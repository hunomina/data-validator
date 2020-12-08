<?php

namespace hunomina\DataValidator\Test\Rule\Json\Factory;

use hunomina\DataValidator\Exception\Json\InvalidRuleException;
use hunomina\DataValidator\Rule\Json\Factory\JsonRuleFactory;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Rule\Json\StringRule;
use PHPUnit\Framework\TestCase;
use Throwable;

/**
 * Class DateFormatCheckTest
 * @package hunomina\DataValidator\Test\Schema\Json\Rule
 * @covers \hunomina\DataValidator\Rule\Json\Factory\JsonRuleFactory
 */
class DateFormatCheckTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param string $type
     */
    public function testTypeDoesNotSupportDateFormatCheck(string $type): void
    {
        try {
            JsonRuleFactory::create($type, ['date-format' => 'Y-m-d']);
        } catch (Throwable $t) {
            self::assertInstanceOf(InvalidRuleException::class, $t);
            self::assertEquals(InvalidRuleException::INVALID_DATE_FORMAT_RULE, $t->getCode());
        }
    }

    public function getTestableData(): array
    {
        return [
            [JsonRule::CHAR_TYPE],
            [JsonRule::NUMERIC_TYPE],
            [JsonRule::INTEGER_TYPE],
            [JsonRule::FLOAT_TYPE],
            [JsonRule::BOOLEAN_TYPE],
            [JsonRule::CHAR_LIST_TYPE],
            [JsonRule::NUMERIC_LIST_TYPE],
            [JsonRule::INTEGER_LIST_TYPE],
            [JsonRule::FLOAT_LIST_TYPE],
            [JsonRule::BOOLEAN_LIST_TYPE]
        ];
    }

    public function testThrowOnInvalidOptionValue(): void
    {
        try {
            JsonRuleFactory::create(JsonRule::STRING_TYPE, ['date-format' => 1]); // not string
        } catch (Throwable $t) {
            self::assertInstanceOf(InvalidRuleException::class, $t);
            self::assertEquals(InvalidRuleException::INVALID_DATE_FORMAT_RULE, $t->getCode());
        }
    }

    /**
     * @throws InvalidRuleException
     */
    public function testRuleCreation(): void
    {
        $dateFormat = 'Y-m-d';
        $rule = JsonRuleFactory::create(JsonRule::STRING_TYPE, ['date-format' => $dateFormat]);
        /** @var StringRule $rule */
        self::assertSame($dateFormat, $rule->getDateFormat());
    }
}