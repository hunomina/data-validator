<?php

namespace hunomina\Validator\Json\Test\Rule;

use hunomina\Validator\Json\Data\JsonData;
use hunomina\Validator\Json\Exception\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use PHPUnit\Framework\TestCase;

class ListTypeTest extends TestCase
{
    /**
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws InvalidDataException
     * @throws InvalidDataException
     */
    public function testValidData(): void
    {
        $data = new JsonData([
            'users' => [
                ['id' => 0, 'name' => 'test0'],
                ['id' => 1, 'name' => 'test1']
            ]
        ]);

        $this->assertTrue(self::getSchema()->validate($data));
    }

    /**
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws InvalidDataException
     * @throws InvalidDataException
     */
    public function testExceptionThrownOnObjectPassedAsAList(): void
    {
        $this->expectException(InvalidDataException::class);
        $this->expectExceptionCode(InvalidDataException::INVALID_LIST_ELEMENT);

        $data = new JsonData([
            'users' => [
                'id' => 0,
                'name' => 'test0'
            ]
        ]);

        self::getSchema()->validate($data);
    }

    /**
     * @return JsonSchema
     * @throws InvalidSchemaException
     */
    private static function getSchema(): JsonSchema
    {
        return new JsonSchema([
            'users' => ['type' => JsonRule::LIST_TYPE, 'schema' => [
                'id' => ['type' => JsonRule::INTEGER_TYPE],
                'name' => ['type' => JsonRule::STRING_TYPE]
            ]]
        ]);
    }
}