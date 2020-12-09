<?php

namespace hunomina\DataValidator\Test\Rule\Json\Factory;

use hunomina\DataValidator\Exception\Json\InvalidRuleException;
use hunomina\DataValidator\Rule\Json\Factory\JsonRuleFactory;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Rule\Json\StringRule;
use PHPUnit\Framework\TestCase;
use Throwable;

/**
 * Class LengthCheckTest
 * @package hunomina\DataValidator\Test\Schema\Json\Rule
 * @covers \hunomina\DataValidator\Rule\Json\Factory\JsonRuleFactory
 */
class LengthCheckTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param string $type
     */
    public function testTypeDoesNotSupportLengthCheck(string $type): void
    {
        try {
            JsonRuleFactory::create($type, ['length' => 10]);
        } catch (Throwable $t) {
            self::assertInstanceOf(InvalidRuleException::class, $t);
            self::assertEquals(InvalidRuleException::INVALID_LENGTH_RULE, $t->getCode());
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

    public function testThrownOnValueInferiorToOne(): void
    {
        try {
            JsonRuleFactory::create(JsonRule::STRING_TYPE, ['length' => 0]); // < 1
        } catch (Throwable $t) {
            self::assertInstanceOf(InvalidRuleException::class, $t);
            self::assertEquals(InvalidRuleException::INVALID_LENGTH_RULE, $t->getCode());
        }
    }

    public function testThrowOnNonIntegerOptionValue(): void
    {
        try {
            JsonRuleFactory::create(JsonRule::STRING_TYPE, ['length' => 'many']); // not integer
        } catch (Throwable $t) {
            self::assertInstanceOf(InvalidRuleException::class, $t);
            self::assertEquals(InvalidRuleException::INVALID_LENGTH_RULE, $t->getCode());
        }
    }

    /**
     * @throws InvalidRuleException
     */
    public function testRuleCreation(): void
    {
        $length = 2;
        $rule = JsonRuleFactory::create(JsonRule::STRING_TYPE, ['length' => $length]);
        /** @var StringRule $rule */
        self::assertSame($length, $rule->getLength());
    }
}