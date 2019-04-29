<?php

namespace hunomina\Validator\Json\Test;

use hunomina\Validator\Json\Data\JsonData;
use hunomina\Validator\Json\Exception\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Schema\DataSchema;
use hunomina\Validator\Json\Schema\JsonSchema;
use PHPUnit\Framework\TestCase;

class FirstLevelJsonValidatorTest extends TestCase
{
    /**
     * @dataProvider getSamples
     * @param string $data
     * @param JsonSchema $schema
     * @param bool $shouldWork
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    public function testValidation(string $data, JsonSchema $schema, bool $shouldWork): void
    {
        $jsonData = (new JsonData())->setData($data);

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
            self::checkErrorCanBeNullSample(),
            self::checkIfErrorCanAlsoBeStringSample(),
            self::checkIfCodeCanBeOmittedSample(),
            self::checkThatCodeCanNotBeNull()
        ];
    }

    /**
     * @return array
     * @throws InvalidSchemaException
     */
    private static function checkErrorCanBeNullSample(): array
    {
        $data = json_encode([
            'success' => true,
            'error' => null,
            'code' => 0
        ]);

        return [$data, self::getBasicSchema(), true];
    }

    /**
     * @return array
     * @throws InvalidSchemaException
     */
    private static function checkIfErrorCanAlsoBeStringSample(): array
    {
        $data = json_encode([
            'success' => false,
            'error' => 'error',
            'code' => 10
        ]);

        return [$data, self::getBasicSchema(), true];
    }

    /**
     * @return array
     * @throws InvalidSchemaException
     */
    private static function checkIfCodeCanBeOmittedSample(): array
    {
        $data = json_encode([
            'success' => false,
            'error' => 'error'
        ]);

        return [$data, self::getBasicSchema(), true];
    }

    /**
     * @return array
     * @throws InvalidSchemaException
     */
    private static function checkThatCodeCanNotBeNull(): array
    {
        $data = json_encode([
            'success' => false,
            'error' => 'error',
            'code' => null // wrong type
        ]);

        return [$data, self::getBasicSchema(), false];
    }

    /**
     * @return DataSchema
     * @throws InvalidSchemaException
     */
    private static function getBasicSchema(): DataSchema
    {
        $schema = [
            'success' => ['type' => 'bool'],
            'error' => ['type' => 'string', 'null' => true],
            'code' => ['type' => 'int', 'optional' => true]
        ];

        return (new JsonSchema())->setSchema($schema);
    }
}