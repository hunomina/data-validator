<?php

namespace hunomina\DataValidator\Test\Schema\Json\Rule\TypedList;

use hunomina\DataValidator\Data\Json\JsonData;
use hunomina\DataValidator\Exception\Json\InvalidDataException;
use hunomina\DataValidator\Exception\Json\InvalidRuleException;
use hunomina\DataValidator\Exception\Json\InvalidSchemaException;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use PHPUnit\Framework\TestCase;
use Throwable;

class ListLengthRuleTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param array $schema
     * @param bool $success
     * @param JsonData|null $data
     * @throws InvalidDataException
     */
    public function testListLengthRule(array $schema, bool $success, ?JsonData $data = null): void
    {
        if (!$success) {
            try {
                new JsonSchema($schema);
            } catch (Throwable $t) {
                self::assertInstanceOf(InvalidSchemaException::class, $t);
                self::assertEquals(InvalidSchemaException::INVALID_SCHEMA_RULE, $t->getCode());

                self::assertInstanceOf(InvalidRuleException::class, $t->getPrevious());
                self::assertEquals(InvalidRuleException::INVALID_LIST_RULE_FOR_SCALAR_TYPE, $t->getPrevious()->getCode());
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
     */
    private static function StringRule(): array
    {
        return [
            ['string' => ['type' => JsonRule::STRING_TYPE, 'list-length' => 1]],
            false
        ];
    }

    /**
     * @return array
     */
    private static function CharacterRule(): array
    {
        return [
            ['character' => ['type' => JsonRule::CHAR_TYPE, 'list-length' => 1]],
            false
        ];
    }

    /**
     * @return array
     */
    private static function NumericRule(): array
    {
        return [
            ['number' => ['type' => JsonRule::NUMERIC_TYPE, 'list-length' => 1]],
            false
        ];
    }

    /**
     * @return array
     */
    private static function IntegerRule(): array
    {
        return [
            ['integer' => ['type' => JsonRule::INTEGER_TYPE, 'list-length' => 1]],
            false
        ];
    }

    /**
     * @return array
     */
    private static function FloatRule(): array
    {
        return [
            ['float' => ['type' => JsonRule::FLOAT_TYPE, 'list-length' => 1]],
            false
        ];
    }

    /**
     * @return array
     */
    private static function BooleanRule(): array
    {
        return [
            ['boolean' => ['type' => JsonRule::BOOLEAN_TYPE, 'list-length' => 1]],
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
            ['string-list' => ['type' => JsonRule::STRING_LIST_TYPE, 'list-length' => 1]],
            true,
            new JsonData([
                'string-list' => ['ok']
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
            ['character-list' => ['type' => JsonRule::CHAR_LIST_TYPE, 'list-length' => 1]],
            true,
            new JsonData([
                'character-list' => ['o']
            ])
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     */
    private static function NumericListRule(): array
    {
        return [
            ['numeric-list' => ['type' => JsonRule::NUMERIC_LIST_TYPE, 'list-length' => 1]],
            true,
            new JsonData([
                'numeric-list' => [1]
            ])
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     */
    private static function IntegerListRule(): array
    {
        return [
            ['integer-list' => ['type' => JsonRule::INTEGER_LIST_TYPE, 'list-length' => 1]],
            true,
            new JsonData([
                'integer-list' => [1]
            ])
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     */
    private static function FloatListRule(): array
    {
        return [
            ['float-list' => ['type' => JsonRule::FLOAT_LIST_TYPE, 'list-length' => 1]],
            true,
            new JsonData([
                'float-list' => [1.0]
            ])
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     */
    private static function BooleanListRule(): array
    {
        return [
            ['boolean-list' => ['type' => JsonRule::BOOLEAN_LIST_TYPE, 'list-length' => 1]],
            true,
            new JsonData([
                'boolean-list' => [true]
            ])
        ];
    }

    public function testInvalidListLengthRuleValueOnTypedList(): void
    {
        try {
            new JsonSchema([
                'string-list' => ['type' => JsonRule::STRING_LIST_TYPE, 'list-length' => 0]
            ]);
        } catch (Throwable $t) {
            self::assertInstanceOf(InvalidSchemaException::class, $t);
            self::assertEquals(InvalidSchemaException::INVALID_SCHEMA_RULE, $t->getCode());

            $t = $t->getPrevious();
            self::assertInstanceOf(InvalidRuleException::class, $t);
            self::assertEquals(InvalidRuleException::INVALID_LIST_LENGTH_RULE, $t->getCode());

            $t = $t->getPrevious();
            self::assertInstanceOf(InvalidRuleException::class, $t);
            self::assertEquals(InvalidRuleException::INVALID_LENGTH_RULE, $t->getCode());
        }
    }
}