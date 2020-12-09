<?php

namespace hunomina\DataValidator\Test\Rule\Json\Factory;

use hunomina\DataValidator\Exception\Json\InvalidRuleException;
use hunomina\DataValidator\Rule\Json\Factory\JsonRuleFactory;
use hunomina\DataValidator\Rule\Json\IntegerRule;
use hunomina\DataValidator\Rule\Json\JsonRule;
use PHPUnit\Framework\TestCase;
use Throwable;

/**
 * Class EnumCheckTest
 * @package hunomina\DataValidator\Test\Schema\Json\Rule
 * @covers \hunomina\DataValidator\Rule\Json\Factory\JsonRuleFactory
 */
class EnumCheckTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param string $type
     */
    public function testTypeDoesNotSupportEnumCheck(string $type): void
    {
        try {
            JsonRuleFactory::create($type, ['enum' => [1, 2, 3]]);
        } catch (Throwable $t) {
            self::assertInstanceOf(InvalidRuleException::class, $t);
            self::assertEquals(InvalidRuleException::INVALID_ENUM_RULE, $t->getCode());
        }
    }

    public function getTestableData(): array
    {
        return [
            [JsonRule::BOOLEAN_TYPE],
            [JsonRule::BOOLEAN_LIST_TYPE]
        ];
    }

    public function testThrowOnInvalidOptionValue(): void
    {
        try {
            JsonRuleFactory::create(JsonRule::INTEGER_TYPE, ['enum' => '[]']); // not array
        } catch (Throwable $t) {
            self::assertInstanceOf(InvalidRuleException::class, $t);
            self::assertEquals(InvalidRuleException::INVALID_ENUM_RULE, $t->getCode());
        }
    }

    /**
     * @throws InvalidRuleException
     */
    public function testRuleCreation(): void
    {
        $enum = [1, 2, 3];
        $rule = JsonRuleFactory::create(JsonRule::INTEGER_TYPE, ['enum' => $enum]);
        /** @var IntegerRule $rule */
        self::assertSame($enum, $rule->getEnum());
    }
}