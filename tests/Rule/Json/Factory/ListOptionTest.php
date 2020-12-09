<?php

namespace hunomina\DataValidator\Test\Rule\Json\Factory;

use hunomina\DataValidator\Exception\Json\InvalidRuleException;
use hunomina\DataValidator\Rule\Json\Factory\JsonRuleFactory;
use hunomina\DataValidator\Rule\Json\JsonRule;
use PHPUnit\Framework\TestCase;
use Throwable;

/**
 * Class ListOptionTest
 * @package hunomina\DataValidator\Test\Rule\Json\Factory
 * @covers \hunomina\DataValidator\Rule\Json\Factory\JsonRuleFactory
 */
class ListOptionTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param string $type
     */
    public function testTypeDoesNotSupportListOption(string $type): void
    {
        try {
            JsonRuleFactory::create($type, ['list-' => '']); // list- will match on list options processing
        } catch (Throwable $t) {
            self::assertInstanceOf(InvalidRuleException::class, $t);
            self::assertEquals(InvalidRuleException::INVALID_LIST_RULE_FOR_SCALAR_TYPE, $t->getCode());
        }
    }

    public function getTestableData(): array
    {
        return [
            [JsonRule::STRING_TYPE],
            [JsonRule::CHAR_TYPE],
            [JsonRule::NUMERIC_TYPE],
            [JsonRule::INTEGER_TYPE],
            [JsonRule::FLOAT_TYPE],
            [JsonRule::BOOLEAN_TYPE]
        ];
    }
}