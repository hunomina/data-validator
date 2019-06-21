<?php

namespace hunomina\Validator\Json\Test;

use hunomina\Validator\Json\Data\JsonData;
use hunomina\Validator\Json\Exception\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Schema\DataSchema;
use hunomina\Validator\Json\Schema\JsonSchema;
use PHPUnit\Framework\TestCase;

class SecondLevelJsonValidatorWithObjectTest extends TestCase
{
    /**
     * @dataProvider getSamples
     * @param array $data
     * @param JsonSchema $schema
     * @param bool $shouldWork
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    public function testValidation(array $data, JsonSchema $schema, bool $shouldWork): void
    {
        $jsonData = (new JsonData())->setDataFromArray($data);

        if ($shouldWork) {
            $this->assertTrue($schema->validate($jsonData));
        } else {
            $this->assertFalse($schema->validate($jsonData));
        }
    }

    /**
     * @return array
     * @throws InvalidSchemaException
     */
    public function getSamples(): array
    {
        return [
            self::checkPerfectData(),
            self::checkWithNullObject(),
            self::checkWithObjectNotSet(),
            self::checkWithoutUser(),
            self::checkWithWrongUser(),
            self::checkWithWrongTypeUser()
        ];
    }

    /**
     * @return array
     * @throws InvalidSchemaException
     * `user` is an object
     */
    private static function checkPerfectData(): array
    {
        $data = [
            'success' => true,
            'error' => null,
            'user' => [
                'name' => 'test',
                'age' => 10
            ]
        ];

        return [$data, self::getSecondLevelSchema(), true];
    }

    /**
     * @return array
     * @throws InvalidSchemaException
     * `user` is null
     */
    private static function checkWithNullObject(): array
    {
        $data = [
            'success' => true,
            'error' => null,
            'user' => null
        ];

        return [$data, self::getSecondLevelSchema(), true];
    }

    /**
     * @return array
     * @throws InvalidSchemaException
     * `user` not set
     */
    private static function checkWithObjectNotSet(): array
    {
        $data = [
            'success' => true,
            'error' => null
        ];

        return [$data, self::getSecondLevelSchema(), true];
    }

    /**
     * @return array
     * @throws InvalidSchemaException
     */
    private static function checkWithoutUser(): array
    {
        $data = [
            'success' => true,
            'error' => null
        ];

        return [$data, self::getSecondLevelSchema(), true];
    }

    /**
     * @return array
     * @throws InvalidSchemaException
     */
    private static function checkWithWrongUser(): array
    {
        $data = [
            'success' => true,
            'error' => null,
            'user' => [
                'pseudo' => 'test'
            ]
        ];

        return [$data, self::getSecondLevelSchema(), false];
    }

    /**
     * @return array
     * @throws InvalidSchemaException
     */
    private static function checkWithWrongTypeUser(): array
    {
        $data = [
            'success' => true,
            'error' => null,
            'user' => 'test'
        ];

        return [$data, self::getSecondLevelSchema(), false];
    }

    /**
     * @return DataSchema
     * @throws InvalidSchemaException
     */
    private static function getSecondLevelSchema(): DataSchema
    {
        $schema = [
            'success' => ['type' => 'bool'],
            'error' => ['type' => 'string', 'null' => true],
            'user' => ['type' => 'object', 'null' => true, 'optional' => true, 'schema' => [
                'name' => ['type' => 'string'],
                'age' => ['type' => 'int']
            ]]
        ];

        return (new JsonSchema())->setSchema($schema);
    }
}