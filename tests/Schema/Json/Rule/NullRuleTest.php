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

class NullRuleTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param array $schema
     * @param bool $success
     * @param JsonData|null $data
     * @throws InvalidDataException
     */
    public function testNullRule(array $schema, bool $success, ?JsonData $data = null): void
    {
        if (!$success) {
            try {
                new JsonSchema($schema);
            } catch (Throwable $t) {
                $this->assertInstanceOf(InvalidSchemaException::class, $t);
                $this->assertEquals(InvalidSchemaException::INVALID_SCHEMA_RULE, $t->getCode());

                $this->assertInstanceOf(InvalidRuleException::class, $t->getPrevious());
                $this->assertEquals(InvalidRuleException::INVALID_NULL_RULE, $t->getPrevious()->getCode());
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
     * @throws InvalidDataException
     */
    private static function StringRule(): array
    {
        return [
            ['string' => ['type' => JsonRule::STRING_TYPE, 'null' => true]],
            true,
            new JsonData([
                'string' => null
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
            ['character' => ['type' => JsonRule::CHAR_TYPE, 'null' => true]],
            true,
            new JsonData([
                'character' => null
            ])
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     */
    private static function NumericRule(): array
    {
        return [
            ['number' => ['type' => JsonRule::NUMERIC_TYPE, 'null' => true]],
            true,
            new JsonData([
                'number' => null
            ])
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     */
    private static function IntegerRule(): array
    {
        return [
            ['integer' => ['type' => JsonRule::INTEGER_TYPE, 'null' => true]],
            true,
            new JsonData([
                'integer' => null
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
            ['float' => ['type' => JsonRule::FLOAT_TYPE, 'null' => true]],
            true,
            new JsonData([
                'float' => null
            ])
        ];
    }

    /**
     * @return array
     */
    private static function BooleanRule(): array
    {
        return [
            ['boolean' => ['type' => JsonRule::BOOLEAN_TYPE, 'null' => true]],
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
            ['string-list' => ['type' => JsonRule::STRING_LIST_TYPE, 'null' => true]],
            true,
            new JsonData([
                'string-list' => [null, 'ok']
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
            ['character-list' => ['type' => JsonRule::CHAR_LIST_TYPE, 'null' => true]],
            true,
            new JsonData([
                'character-list' => [null, 'o', 'k']
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
            ['numeric-list' => ['type' => JsonRule::NUMERIC_LIST_TYPE, 'null' => true]],
            true,
            new JsonData([
                'numeric-list' => [null, 1, 2.0]
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
            ['integer-list' => ['type' => JsonRule::INTEGER_LIST_TYPE, 'null' => true]],
            true,
            new JsonData([
                'integer-list' => [null, 1]
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
            ['float-list' => ['type' => JsonRule::FLOAT_LIST_TYPE, 'null' => true]],
            true,
            new JsonData([
                'float-list' => [null, 1.0]
            ])
        ];
    }

    /**
     * @return array
     */
    private static function BooleanListRule(): array
    {
        return [
            ['boolean-list' => ['type' => JsonRule::BOOLEAN_LIST_TYPE, 'null' => true]],
            false
        ];
    }
}