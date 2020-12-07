<?php

namespace hunomina\DataValidator\Test\Schema\Json\Rule;

use hunomina\DataValidator\Data\Json\JsonData;
use hunomina\DataValidator\Exception\Json\InvalidSchemaException;
use hunomina\DataValidator\Exception\Json\InvalidDataException;
use hunomina\DataValidator\Exception\Json\InvalidRuleException;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use PHPUnit\Framework\TestCase;
use Throwable;

class PatternRuleTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param array $schema
     * @param bool $success
     * @param JsonData|null $data
     * @throws InvalidDataException
     */
    public function testPatternRule(array $schema, bool $success, ?JsonData $data = null): void
    {
        if (!$success) {
            try {
                new JsonSchema($schema);
            } catch (Throwable $t) {
                self::assertInstanceOf(InvalidSchemaException::class, $t);
                self::assertEquals(InvalidSchemaException::INVALID_SCHEMA_RULE, $t->getCode());

                self::assertInstanceOf(InvalidRuleException::class, $t->getPrevious());
                self::assertEquals(InvalidRuleException::INVALID_PATTERN_RULE, $t->getPrevious()->getCode());
            }
        } else {
            $schema = new JsonSchema($schema);
            self::assertTrue($schema->validate($data));
        }
    }

    /**
     * @return array
     * @throws InvalidDataException
     */
    public function getTestableData(): array
    {
        return [
            self::StringRule(),
            self::CharacterRule(),
            self::NumericRule(),
            self::IntegerRule(),
            self::FloatRule(),
            self::BooleanRule(),
            self::StringListRule(),
            self::CharacterListRule(),
            self::NumericListRule(),
            self::IntegerListRule(),
            self::FloatListRule(),
            self::BooleanListRule()
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     */
    private static function StringRule(): array
    {
        return [
            ['string' => ['type' => JsonRule::STRING_TYPE, 'pattern' => '/[a-z]+/']],
            true,
            new JsonData([
                'string' => 'work'
            ])
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     */
    private static function CharacterRule(): array
    {
        return [
            ['character' => ['type' => JsonRule::CHAR_TYPE, 'pattern' => '/[a-z]/']],
            true,
            new JsonData([
                'character' => 'a'
            ])
        ];
    }

    /**
     * @return array
     */
    private static function NumericRule(): array
    {
        return [
            ['number' => ['type' => JsonRule::NUMERIC_TYPE, 'pattern' => '/\d+/']],
            false
        ];
    }

    /**
     * @return array
     */
    private static function IntegerRule(): array
    {
        return [
            ['integer' => ['type' => JsonRule::INTEGER_TYPE, 'pattern' => '/\d+/']],
            false
        ];
    }

    /**
     * @return array
     */
    private static function FloatRule(): array
    {
        return [
            ['float' => ['type' => JsonRule::FLOAT_TYPE, 'pattern' => '/\d+/']],
            false
        ];
    }

    /**
     * @return array
     */
    private static function BooleanRule(): array
    {
        return [
            ['boolean' => ['type' => JsonRule::BOOLEAN_TYPE, 'pattern' => '/true|false/']],
            false
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     */
    private static function StringListRule(): array
    {
        return [
            ['string-list' => ['type' => JsonRule::STRING_LIST_TYPE, 'pattern' => '/[a-z]+/']],
            true,
            new JsonData([
                'string-list' => ['this', 'one', 'should', 'work']
            ])
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     */
    private static function CharacterListRule(): array
    {
        return [
            ['character-list' => ['type' => JsonRule::CHAR_LIST_TYPE, 'pattern' => '/[a-zA-Z]/']],
            true,
            new JsonData([
                'character-list' => ['a', 'b', 'C', 'd']
            ])
        ];
    }

    /**
     * @return array
     */
    private static function NumericListRule(): array
    {
        return [
            ['numeric-list' => ['type' => JsonRule::NUMERIC_LIST_TYPE, 'pattern' => '/\d+/']],
            false
        ];
    }

    /**
     * @return array
     */
    private static function IntegerListRule(): array
    {
        return [
            ['integer-list' => ['type' => JsonRule::INTEGER_LIST_TYPE, 'pattern' => '/\d+/']],
            false
        ];
    }

    /**
     * @return array
     */
    private static function FloatListRule(): array
    {
        return [
            ['float-list' => ['type' => JsonRule::FLOAT_LIST_TYPE, 'pattern' => '/\d+/']],
            false
        ];
    }

    /**
     * @return array
     */
    private static function BooleanListRule(): array
    {
        return [
            ['boolean-list' => ['type' => JsonRule::BOOLEAN_LIST_TYPE, 'pattern' => '/true|false/']],
            false
        ];
    }
}