<?php

namespace hunomina\Validator\Json\Test\Rule;

use hunomina\Validator\Json\Data\JsonData;
use hunomina\Validator\Json\Exception\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use PHPUnit\Framework\TestCase;

class EmptyCheckTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param JsonData $data
     * @param JsonSchema $schema
     * @param bool $success
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    public function testEmptyCheck(JsonData $data, JsonSchema $schema, bool $success): void
    {
        if (!$success) {
            $this->expectException(InvalidDataException::class);
            $this->expectExceptionCode(InvalidDataException::EMPTY_VALUE_NOT_ALLOWED);

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
            self::EmptyRuleOnString(),
            self::EmptyRuleOnStringFail(),
            self::EmptyRuleOnStringTypedList(),
            self::EmptyRuleOnStringTypedListFail(),
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function EmptyRuleOnString(): array
    {
        return [
            new JsonData([
                'name' => ''
            ]),
            new JsonSchema([
                'name' => ['type' => JsonRule::STRING_TYPE, 'empty' => true]
            ]),
            true
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function EmptyRuleOnStringFail(): array
    {
        return [
            new JsonData([
                'name' => ''
            ]),
            new JsonSchema([
                'name' => ['type' => JsonRule::STRING_TYPE, 'empty' => false] // default behavior
            ]),
            false
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function EmptyRuleOnStringTypedList(): array
    {
        return [
            new JsonData([
                'list' => []
            ]),
            new JsonSchema([
                'list' => ['type' => JsonRule::STRING_LIST_TYPE, 'empty' => true]
            ]),
            true
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function EmptyRuleOnStringTypedListFail(): array
    {
        return [
            new JsonData([
                'list' => []
            ]),
            new JsonSchema([
                'list' => ['type' => JsonRule::STRING_LIST_TYPE, 'empty' => false] // default behavior
            ]),
            false
        ];
    }
}