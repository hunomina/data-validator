<?php

namespace hunomina\DataValidator\Test\Rule\Json\Traits;

use hunomina\DataValidator\Data\Json\JsonData;
use hunomina\DataValidator\Exception\Json\InvalidDataException;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use PHPUnit\Framework\TestCase;

class LengthCheckTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param JsonData $data
     * @param JsonSchema $schema
     * @param bool $success
     * @throws InvalidDataException
     */
    public function testLengthCheck(JsonData $data, JsonSchema $schema, bool $success): void
    {
        if (!$success) {
            $this->expectException(InvalidDataException::class);
            $this->expectExceptionCode(InvalidDataException::INVALID_LENGTH);

            $schema->validate($data);
        } else {
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
            self::StringLengthCheck(),
            self::StringLengthCheckFail(),
            self::TypedListLengthCheck(),
            self::TypedListLengthCheckFail()
        ];
    }

    /**
     * @throws InvalidDataException
     */
    private static function StringLengthCheck(): array
    {
        return [
            new JsonData([
                'username' => 'test'
            ]),
            new JsonSchema([
                'username' => ['type' => JsonRule::STRING_TYPE, 'length' => 4]
            ]),
            true
        ];
    }

    /**
     * @throws InvalidDataException
     */
    private static function StringLengthCheckFail(): array
    {
        return [
            new JsonData([
                'username' => 'test2'
            ]),
            new JsonSchema([
                'username' => ['type' => JsonRule::STRING_TYPE, 'length' => 4]
            ]),
            false
        ];
    }

    /**
     * @throws InvalidDataException
     */
    private static function TypedListLengthCheck(): array
    {
        return [
            new JsonData([
                'users' => [1, 2, 3, 4]
            ]),
            new JsonSchema([
                'users' => ['type' => JsonRule::INTEGER_LIST_TYPE, 'list-length' => 4]
            ]),
            true
        ];
    }

    /**
     * @throws InvalidDataException
     */
    private static function TypedListLengthCheckFail(): array
    {
        return [
            new JsonData([
                'users' => [1, 2, 3, 4, 5]
            ]),
            new JsonSchema([
                'users' => ['type' => JsonRule::INTEGER_LIST_TYPE, 'list-length' => 4]
            ]),
            false
        ];
    }
}