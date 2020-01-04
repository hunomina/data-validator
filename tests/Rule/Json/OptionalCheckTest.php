<?php

namespace hunomina\Validator\Json\Test\Rule\Json;

use hunomina\Validator\Json\Data\Json\JsonData;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\Json\InvalidDataException;
use hunomina\Validator\Json\Exception\Json\InvalidSchemaException;
use hunomina\Validator\Json\Rule\Json\JsonRule;
use hunomina\Validator\Json\Schema\Json\JsonSchema;
use PHPUnit\Framework\TestCase;

class OptionalCheckTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param JsonData $data
     * @param JsonSchema $schema
     * @param bool $success
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    public function testOptionalCheck(JsonData $data, JsonSchema $schema, bool $success): void
    {
        if (!$success) {
            $this->expectException(InvalidDataException::class);
            $this->expectExceptionCode(InvalidDataException::MANDATORY_FIELD);

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
            self::OptionalFieldCheck(),
            self::OptionalFieldCheckFail(),
            self::OptionalObjectCheck(),
            self::OptionalObjectCheckFail()
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function OptionalFieldCheck(): array
    {
        return [
            new JsonData([]),
            new JsonSchema([
                'fieldobject' => ['type' => JsonRule::STRING_TYPE, 'optional' => true]
            ]),
            true
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function OptionalFieldCheckFail(): array
    {
        return [
            new JsonData([]),
            new JsonSchema([
                'fieldobject' => ['type' => JsonRule::STRING_TYPE, 'optional' => false] // default behavior
            ]),
            false
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function OptionalObjectCheck(): array
    {
        return [
            new JsonData([]),
            new JsonSchema([
                'object' => ['type' => JsonRule::OBJECT_TYPE, 'optional' => true, 'schema' => []]
            ]),
            true
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function OptionalObjectCheckFail(): array
    {
        return [
            new JsonData([]),
            new JsonSchema([
                'object' => ['type' => JsonRule::OBJECT_TYPE, 'optional' => false, 'schema' => []] // default behavior
            ]),
            false
        ];
    }
}