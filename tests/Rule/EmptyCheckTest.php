<?php

namespace hunomina\Validator\Json\Test\Rule;

use hunomina\Validator\Json\Data\JsonData;
use hunomina\Validator\Json\Exception\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use PHPUnit\Framework\TestCase;

// todo : add schema build test in an other test in Test\Schema

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
            $this->EmptyRuleOnString(),
            $this->EmptyRuleOnStringFail(),
            $this->EmptyRuleOnStringTypedList(),
            $this->EmptyRuleOnStringTypedListFail(),
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    public function EmptyRuleOnString(): array
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
    public function EmptyRuleOnStringFail(): array
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
    public function EmptyRuleOnStringTypedList(): array
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
    public function EmptyRuleOnStringTypedListFail(): array
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