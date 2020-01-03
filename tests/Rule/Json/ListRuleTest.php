<?php

namespace hunomina\Validator\Json\Test\Rule\Json;

use hunomina\Validator\Json\Data\Json\JsonData;
use hunomina\Validator\Json\Exception\Json\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\Json\InvalidSchemaException;
use hunomina\Validator\Json\Rule\Json\JsonRule;
use hunomina\Validator\Json\Schema\Json\JsonSchema;
use PHPUnit\Framework\TestCase;

class ListRuleTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param JsonData $data
     * @param JsonSchema $schema
     * @param bool $success
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    public function testListType(JsonData $data, JsonSchema $schema, bool $success): void
    {
        if (!$success) {
            $this->expectException(InvalidDataException::class);
            $this->expectExceptionCode(InvalidDataException::INVALID_LIST_ELEMENT);

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
            self::ValidList(),
            self::InvalidList(),
            self::NotAnObjectList()
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function ValidList(): array
    {
        return [
            new JsonData([
                'users' => [
                    ['id' => 0, 'name' => 'test0'],
                    ['id' => 1, 'name' => 'test1']
                ]
            ]),
            new JsonSchema([
                'users' => ['type' => JsonRule::LIST_TYPE, 'schema' => [
                    'id' => ['type' => JsonRule::INTEGER_TYPE],
                    'name' => ['type' => JsonRule::STRING_TYPE]
                ]]
            ]),
            true
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function InvalidList(): array
    {
        return [
            new JsonData([
                'users' => [ // elements do not match the schema
                    ['id' => 0],
                    ['id' => 1],
                ]
            ]),
            new JsonSchema([
                'users' => ['type' => JsonRule::LIST_TYPE, 'schema' => [
                    'id' => ['type' => JsonRule::INTEGER_TYPE],
                    'name' => ['type' => JsonRule::STRING_TYPE]
                ]]
            ]),
            false
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function NotAnObjectList(): array
    {
        return [
            new JsonData([
                'users' => [
                    'id' => 0,
                    'name' => 'test0'
                ]
            ]),
            new JsonSchema([
                'users' => ['type' => JsonRule::LIST_TYPE, 'schema' => [
                    'id' => ['type' => JsonRule::INTEGER_TYPE],
                    'name' => ['type' => JsonRule::STRING_TYPE]
                ]]
            ]),
            false
        ];
    }
}