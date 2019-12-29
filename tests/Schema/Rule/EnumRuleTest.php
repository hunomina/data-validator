<?php

namespace hunomina\Validator\Json\Test\Schema\Rule;

use hunomina\Validator\Json\Data\JsonData;
use hunomina\Validator\Json\Exception\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use PHPUnit\Framework\TestCase;

class EnumRuleTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param array $schema
     * @param bool $success
     * @param JsonData|null $data
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     * @throws InvalidDataTypeException
     */
    public function testEnumRule(array $schema, bool $success, ?JsonData $data = null): void
    {
        if (!$success) {
            $this->expectException(InvalidSchemaException::class);
            $this->expectExceptionCode(InvalidSchemaException::INVALID_ENUM_RULE);
        }

        $schema = new JsonSchema($schema);

        if ($success) {
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
            ['string' => ['type' => JsonRule::STRING_TYPE, 'enum' => ['ok', 'ko']]],
            true,
            new JsonData([
                'string' => 'ok'
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
            ['character' => ['type' => JsonRule::CHAR_TYPE, 'enum' => ['o', 'k']]],
            true,
            new JsonData([
                'character' => 'o'
            ])
        ];
    }

    /**
     * @return array
     */
    private static function NumericRule(): array
    {
        return [
            ['number' => ['type' => JsonRule::NUMERIC_TYPE, 'enum' => [1, 2.0]]],
            false
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     */
    private static function IntegerRule(): array
    {
        return [
            ['integer' => ['type' => JsonRule::INTEGER_TYPE, 'enum' => [1, 2]]],
            true,
            new JsonData([
                'integer' => 2
            ])
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     */
    private static function FloatRule(): array
    {
        return [
            ['float' => ['type' => JsonRule::FLOAT_TYPE, 'enum' => [1.0, 2.0]]],
            true,
            new JsonData([
                'float' => 2.0
            ])
        ];
    }

    /**
     * @return array
     */
    private static function BooleanRule(): array
    {
        return [
            ['boolean' => ['type' => JsonRule::BOOLEAN_TYPE, 'enum' => [true, false]]],
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
            ['string-list' => ['type' => JsonRule::STRING_LIST_TYPE, 'enum' => ['ok', 'ko']]],
            true,
            new JsonData([
                'string-list' => ['ok', 'ko']
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
            ['character-list' => ['type' => JsonRule::CHAR_LIST_TYPE, 'enum' => ['o', 'k']]],
            true,
            new JsonData([
                'character-list' => ['o', 'k']
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
            ['numeric-list' => ['type' => JsonRule::NUMERIC_LIST_TYPE, 'enum' => [1, 2.0]]],
            true,
            new JsonData([
                'numeric-list' => [1, 2.0]
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
            ['integer-list' => ['type' => JsonRule::INTEGER_LIST_TYPE, 'enum' => [1, 2]]],
            true,
            new JsonData([
                'integer-list' => [1, 2]
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
            ['float-list' => ['type' => JsonRule::FLOAT_LIST_TYPE, 'enum' => [1.0, 2.0]]],
            true,
            new JsonData([
                'float-list' => [1.0, 2.0]
            ])
        ];
    }

    /**
     * @return array
     */
    private static function BooleanListRule(): array
    {
        return [
            ['boolean-list' => ['type' => JsonRule::BOOLEAN_LIST_TYPE, 'enum' => [true, false]]], // check the list length
            false
        ];
    }
}