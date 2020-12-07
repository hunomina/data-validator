<?php

namespace hunomina\DataValidator\Test\Rule\Json\Traits;

use hunomina\DataValidator\Data\Json\JsonData;
use hunomina\DataValidator\Exception\Json\InvalidDataException;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use PHPUnit\Framework\TestCase;

class NullCheckTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param JsonData $data
     * @param JsonSchema $schema
     * @param bool $success
     * @throws InvalidDataException
     */
    public function testNullCheck(JsonData $data, JsonSchema $schema, bool $success): void
    {
        if (!$success) {
            $this->expectException(InvalidDataException::class);
            $this->expectExceptionCode(InvalidDataException::NULL_VALUE_NOT_ALLOWED);

            $schema->validate($data);
        } else {
            self::assertTrue($schema->validate($data));
        }
    }

    /**
     * @return array
     * @throws InvalidDataException
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