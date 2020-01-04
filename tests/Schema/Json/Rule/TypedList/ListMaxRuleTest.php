<?php

namespace hunomina\DataValidator\Test\Schema\Json\Rule\TypedList;

use hunomina\DataValidator\Data\Json\JsonData;
use hunomina\DataValidator\Exception\InvalidDataTypeException;
use hunomina\DataValidator\Exception\Json\InvalidDataException;
use hunomina\DataValidator\Exception\Json\InvalidRuleException;
use hunomina\DataValidator\Exception\Json\InvalidSchemaException;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use PHPUnit\Framework\TestCase;
use Throwable;

class ListMaxRuleTest extends TestCase
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
    public function testListMaxRule(array $schema, bool $success, ?JsonData $data = null): void
    {
        if (!$success) {
            try {
                new JsonSchema($schema);
            } catch (Throwable $t) {
                $this->assertInstanceOf(InvalidSchemaException::class, $t);
                $this->assertEquals(InvalidSchemaException::INVALID_SCHEMA_RULE, $t->getCode());

                $this->assertInstanceOf(InvalidRuleException::class, $t->getPrevious());
                $this->assertEquals(InvalidRuleException::INVALID_LIST_RULE_FOR_SCALAR_TYPE, $t->getPrevious()->getCode());
            }
        } else {
            $schema = new JsonSchema($schema);
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
     */
    private static function StringRule(): array
    {
        return [
            ['string' => ['type' => JsonRule::STRING_TYPE, 'list-max' => 1]],
            false
        ];
    }

    /**
     * @return array
     */
    private static function CharacterRule(): array
    {
        return [
            ['character' => ['type' => JsonRule::CHAR_TYPE, 'list-max' => 1]],
            false
        ];
    }

    /**
     * @return array
     */
    private static function NumericRule(): array
    {
        return [
            ['number' => ['type' => JsonRule::NUMERIC_TYPE, 'list-max' => 1]],
            false
        ];
    }

    /**
     * @return array
     */
    private static function IntegerRule(): array
    {
        return [
            ['integer' => ['type' => JsonRule::INTEGER_TYPE, 'list-max' => 1]],
            false
        ];
    }

    /**
     * @return array
     */
    private static function FloatRule(): array
    {
        return [
            ['float' => ['type' => JsonRule::FLOAT_TYPE, 'list-max' => 1]],
            false
        ];
    }

    /**
     * @return array
     */
    private static function BooleanRule(): array
    {
        return [
            ['boolean' => ['type' => JsonRule::BOOLEAN_TYPE, 'list-max' => 1]],
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
            ['string-list' => ['type' => JsonRule::STRING_LIST_TYPE, 'list-max' => 1]],
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
            ['character-list' => ['type' => JsonRule::CHAR_LIST_TYPE, 'list-max' => 1]],
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
            ['numeric-list' => ['type' => JsonRule::NUMERIC_LIST_TYPE, 'list-max' => 1]],
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
            ['integer-list' => ['type' => JsonRule::INTEGER_LIST_TYPE, 'list-max' => 1]],
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
            ['float-list' => ['type' => JsonRule::FLOAT_LIST_TYPE, 'list-max' => 1]],
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
            ['boolean-list' => ['type' => JsonRule::BOOLEAN_LIST_TYPE, 'list-max' => 1]],
            true,
            new JsonData([
                'boolean-list' => [true]
            ])
        ];
    }

    public function testInvalidListMaxRuleValueOnTypedList(): void
    {
        try {
            new JsonSchema([
                'string-list' => ['type' => JsonRule::STRING_LIST_TYPE, 'list-max' => 0]
            ]);
        } catch (Throwable $t) {
            $this->assertInstanceOf(InvalidSchemaException::class, $t);
            $this->assertEquals(InvalidSchemaException::INVALID_SCHEMA_RULE, $t->getCode());

            $t = $t->getPrevious();
            $this->assertInstanceOf(InvalidRuleException::class, $t);
            $this->assertEquals(InvalidRuleException::INVALID_LIST_MAX_RULE, $t->getCode());
        }
    }
}