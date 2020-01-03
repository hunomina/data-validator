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

class DateFormatRuleTest extends TestCase
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
    public function testDateFormatRule(array $schema, bool $success, ?JsonData $data = null): void
    {
        if (!$success) {
            $this->expectException(InvalidSchemaException::class);
            $this->expectExceptionCode(InvalidSchemaException::INVALID_DATE_FORMAT_RULE);
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
            ['string' => ['type' => JsonRule::STRING_TYPE, 'date-format' => 'Y-m-d']],
            true,
            new JsonData([
                'string' => '2000-01-01'
            ])
        ];
    }

    /**
     * @return array
     */
    private static function CharacterRule(): array
    {
        return [
            ['character' => ['type' => JsonRule::CHAR_TYPE, 'date-format' => 'Y-m-d']],
            false
        ];
    }

    /**
     * @return array
     */
    private static function NumericRule(): array
    {
        return [
            ['number' => ['type' => JsonRule::NUMERIC_TYPE, 'date-format' => 'Y-m-d']],
            false
        ];
    }

    /**
     * @return array
     */
    private static function IntegerRule(): array
    {
        return [
            ['integer' => ['type' => JsonRule::INTEGER_TYPE, 'date-format' => 'Y-m-d']],
            false
        ];
    }

    /**
     * @return array
     */
    private static function FloatRule(): array
    {
        return [
            ['float' => ['type' => JsonRule::FLOAT_TYPE, 'date-format' => 'Y-m-d']],
            false
        ];
    }

    /**
     * @return array
     */
    private static function BooleanRule(): array
    {
        return [
            ['boolean' => ['type' => JsonRule::BOOLEAN_TYPE, 'date-format' => 'Y-m-d']],
            false
        ];
    }

    /**
     * @return array
     */
    private static function StringListRule(): array
    {
        return [
            ['string-list' => ['type' => JsonRule::STRING_LIST_TYPE, 'date-format' => 'Y-m-d']],
            false
        ];
    }

    /**
     * @return array
     */
    private static function CharacterListRule(): array
    {
        return [
            ['character-list' => ['type' => JsonRule::CHAR_LIST_TYPE, 'date-format' => 'Y-m-d']],
            false
        ];
    }

    /**
     * @return array
     */
    private static function NumericListRule(): array
    {
        return [
            ['numeric-list' => ['type' => JsonRule::NUMERIC_LIST_TYPE, 'date-format' => 'Y-m-d']],
            false
        ];
    }

    /**
     * @return array
     */
    private static function IntegerListRule(): array
    {
        return [
            ['integer-list' => ['type' => JsonRule::INTEGER_LIST_TYPE, 'date-format' => 'Y-m-d']],
            false
        ];
    }

    /**
     * @return array
     */
    private static function FloatListRule(): array
    {
        return [
            ['float-list' => ['type' => JsonRule::FLOAT_LIST_TYPE, 'date-format' => 'Y-m-d']],
            false
        ];
    }

    /**
     * @return array
     */
    private static function BooleanListRule(): array
    {
        return [
            ['boolean-list' => ['type' => JsonRule::BOOLEAN_LIST_TYPE, 'date-format' => 'Y-m-d']],
            false
        ];
    }
}