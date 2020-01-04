<?php

namespace hunomina\Validator\Json\Test\Rule\Json\Traits;

use hunomina\Validator\Json\Data\Json\JsonData;
use hunomina\Validator\Json\Exception\Json\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\Json\InvalidSchemaException;
use hunomina\Validator\Json\Rule\Json\JsonRule;
use hunomina\Validator\Json\Schema\Json\JsonSchema;
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
                'list' => ['type' => JsonRule::STRING_LIST_TYPE, 'list-empty' => true]
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
                'list' => ['type' => JsonRule::STRING_LIST_TYPE, 'list-empty' => false] // default behavior
            ]),
            false
        ];
    }
}