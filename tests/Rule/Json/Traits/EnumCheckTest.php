<?php

namespace hunomina\Validator\Json\Test\Rule\Json\Traits;

use hunomina\Validator\Json\Data\Json\JsonData;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\Json\InvalidDataException;
use hunomina\Validator\Json\Exception\Json\InvalidSchemaException;
use hunomina\Validator\Json\Rule\Json\JsonRule;
use hunomina\Validator\Json\Schema\Json\JsonSchema;
use PHPUnit\Framework\TestCase;

class EnumCheckTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param JsonData $data
     * @param JsonSchema $schema
     * @param bool $success
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    public function testEnumCheck(JsonData $data, JsonSchema $schema, bool $success): void
    {
        if (!$success) {
            $this->expectException(InvalidDataException::class);
            $this->expectExceptionCode(InvalidDataException::UNAUTHORIZED_VALUE);

            $schema->validate($data);
        } else {
            $this->assertTrue($schema->validate($data));
        }
    }

    /**
     * @return array
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    public function getTestableData(): array
    {
        return [
            self::EnumForString(),
            self::EnumForStringFail(),
            self::EnumForInteger(),
            self::EnumForIntegerFail(),
            self::EnumForFloat(),
            self::EnumForFloatFail(),
            self::EnumForNumber(),
            self::EnumForNumberFail(),
            self::EnumForCharacter(),
            self::EnumForCharacterFail()
        ];
    }

    /**
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function EnumForString(): array
    {
        return [
            new JsonData([
                'gender' => 'female'
            ]),
            new JsonSchema([
                'gender' => ['type' => JsonRule::STRING_TYPE, 'enum' => ['male', 'female']]
            ]),
            true
        ];
    }

    /**
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function EnumForStringFail(): array
    {
        return [
            new JsonData([
                'gender' => 'fish'
            ]),
            new JsonSchema([
                'gender' => ['type' => JsonRule::STRING_TYPE, 'enum' => ['male', 'female']]
            ]),
            false
        ];
    }

    /**
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function EnumForInteger(): array
    {
        return [
            new JsonData([
                'feet' => 2
            ]),
            new JsonSchema([
                'feet' => ['type' => JsonRule::INTEGER_TYPE, 'enum' => [2, 3, 4]]
            ]),
            true
        ];
    }

    /**
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function EnumForIntegerFail(): array
    {
        return [
            new JsonData([
                'feet' => 5
            ]),
            new JsonSchema([
                'feet' => ['type' => JsonRule::INTEGER_TYPE, 'enum' => [2, 3, 4]]
            ]),
            false
        ];
    }

    /**
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function EnumForFloat(): array
    {
        return [
            new JsonData([
                'feet' => 2.0
            ]),
            new JsonSchema([
                'feet' => ['type' => JsonRule::FLOAT_TYPE, 'enum' => [2.0, 3.0, 4.0]]
            ]),
            true
        ];
    }

    /**
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function EnumForFloatFail(): array
    {
        return [
            new JsonData([
                'feet' => 5.0
            ]),
            new JsonSchema([
                'feet' => ['type' => JsonRule::FLOAT_TYPE, 'enum' => [2.0, 3.0, 4.0]]
            ]),
            false
        ];
    }

    /**
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function EnumForNumber(): array
    {
        return [
            new JsonData([
                'feet' => 2.0
            ]),
            new JsonSchema([
                'feet' => ['type' => JsonRule::NUMERIC_TYPE, 'enum' => [2.0, 3, 4.0]]
            ]),
            true
        ];
    }

    /**
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function EnumForNumberFail(): array
    {
        return [
            new JsonData([
                'feet' => 5.0
            ]),
            new JsonSchema([
                'feet' => ['type' => JsonRule::NUMERIC_TYPE, 'enum' => [2.0, 3, 4.0]]
            ]),
            false
        ];
    }

    /**
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function EnumForCharacter(): array
    {
        return [
            new JsonData([
                'blood_type' => 'A'
            ]),
            new JsonSchema([
                'blood_type' => ['type' => JsonRule::CHAR_TYPE, 'enum' => ['A', 'B', 'O']]
            ]),
            true
        ];
    }

    /**
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function EnumForCharacterFail(): array
    {
        return [
            new JsonData([
                'blood_type' => 'C'
            ]),
            new JsonSchema([
                'blood_type' => ['type' => JsonRule::CHAR_TYPE, 'enum' => ['A', 'B', 'O']]
            ]),
            false
        ];
    }
}