<?php

namespace hunomina\Validator\Json\Test\Schema\Rule;

use hunomina\Validator\Json\Data\Json\JsonData;
use hunomina\Validator\Json\Exception\Json\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\Json\InvalidSchemaException;
use hunomina\Validator\Json\Exception\Json\InvalidRuleException;
use hunomina\Validator\Json\Rule\Json\JsonRule;
use hunomina\Validator\Json\Schema\Json\JsonSchema;
use PHPUnit\Framework\TestCase;

class EmptyRuleTest extends TestCase
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
    public function testEmptyRule(array $schema, bool $success, ?JsonData $data = null): void
    {
        if (!$success) {
            $this->expectException(InvalidSchemaException::class);
            $this->expectExceptionCode(InvalidSchemaException::INVALID_EMPTY_RULE);
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
            ['string' => ['type' => JsonRule::STRING_TYPE, 'empty' => true]],
            true,
            new JsonData([
                'string' => ''
            ])
        ];
    }

    /**
     * @return array
     */
    private static function CharacterRule(): array
    {
        return [
            ['character' => ['type' => JsonRule::CHAR_TYPE, 'empty' => true]],
            false
        ];
    }

    /**
     * @return array
     */
    private static function NumericRule(): array
    {
        return [
            ['number' => ['type' => JsonRule::NUMERIC_TYPE, 'empty' => true]],
            false
        ];
    }

    /**
     * @return array
     */
    private static function IntegerRule(): array
    {
        return [
            ['integer' => ['type' => JsonRule::INTEGER_TYPE, 'empty' => true]],
            false
        ];
    }

    /**
     * @return array
     */
    private static function FloatRule(): array
    {
        return [
            ['float' => ['type' => JsonRule::FLOAT_TYPE, 'empty' => true]],
            false
        ];
    }

    /**
     * @return array
     */
    private static function BooleanRule(): array
    {
        return [
            ['boolean' => ['type' => JsonRule::BOOLEAN_TYPE, 'empty' => true]],
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
            ['string-list' => ['type' => JsonRule::STRING_LIST_TYPE, 'empty' => true]],
            true,
            new JsonData([
                'string-list' => []
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
            ['character-list' => ['type' => JsonRule::CHAR_LIST_TYPE, 'empty' => true]],
            true,
            new JsonData([
                'character-list' => []
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
            ['numeric-list' => ['type' => JsonRule::NUMERIC_LIST_TYPE, 'empty' => true]],
            true,
            new JsonData([
                'numeric-list' => []
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
            ['integer-list' => ['type' => JsonRule::INTEGER_LIST_TYPE, 'empty' => true]],
            true,
            new JsonData([
                'integer-list' => []
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
            ['float-list' => ['type' => JsonRule::FLOAT_LIST_TYPE, 'empty' => true]],
            true,
            new JsonData([
                'float-list' => []
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
            ['boolean-list' => ['type' => JsonRule::BOOLEAN_LIST_TYPE, 'empty' => true]],
            true,
            new JsonData([
                'boolean-list' => []
            ])
        ];
    }
}