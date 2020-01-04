<?php

namespace hunomina\Validator\Json\Test\Rule\Json\Traits;

use hunomina\Validator\Json\Data\Json\JsonData;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\Json\InvalidDataException;
use hunomina\Validator\Json\Exception\Json\InvalidSchemaException;
use hunomina\Validator\Json\Rule\Json\JsonRule;
use hunomina\Validator\Json\Schema\Json\JsonSchema;
use PHPUnit\Framework\TestCase;

class NullCheckTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param JsonData $data
     * @param JsonSchema $schema
     * @param bool $success
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    public function testNullCheck(JsonData $data, JsonSchema $schema, bool $success): void
    {
        if (!$success) {
            $this->expectException(InvalidDataException::class);
            $this->expectExceptionCode(InvalidDataException::NULL_VALUE_NOT_ALLOWED);

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
            self::NullCheck(),
            self::NullCheckFail(),
            self::NullListCheck(),
            self::NullListCheckFail()
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function NullCheck(): array
    {
        return [
            new JsonData([
                'value' => null
            ]),
            new JsonSchema([
                'value' => ['type' => JsonRule::STRING_TYPE, 'null' => true]
            ]),
            true
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function NullCheckFail(): array
    {
        return [
            new JsonData([
                'value' => null
            ]),
            new JsonSchema([
                'value' => ['type' => JsonRule::STRING_TYPE, 'null' => false] // default behavior
            ]),
            false
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function NullListCheck(): array
    {
        return [
            new JsonData([
                'value' => null
            ]),
            new JsonSchema([
                'value' => ['type' => JsonRule::STRING_LIST_TYPE, 'list-null' => true]
            ]),
            true
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function NullListCheckFail(): array
    {
        return [
            new JsonData([
                'value' => null
            ]),
            new JsonSchema([
                'value' => ['type' => JsonRule::STRING_LIST_TYPE, 'list-null' => false] // default behavior
            ]),
            false
        ];
    }
}