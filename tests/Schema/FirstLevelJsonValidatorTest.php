<?php

namespace hunomina\Validator\Json\Test\Schema;

use hunomina\Validator\Json\Data\JsonData;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\DataSchema;
use hunomina\Validator\Json\Schema\JsonSchema;
use PHPUnit\Framework\TestCase;

class FirstLevelJsonValidatorTest extends TestCase
{
    /**
     * @dataProvider getSamples
     * @param array $data
     * @param JsonSchema $schema
     * @param bool $shouldWork
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
        $data = [
            'success' => true,
            'error' => null,
            'code' => 0
        ];

        return [$data, self::getBasicSchema(), true];
    }

    /**
     * @return array
     * @throws InvalidSchemaException
     */
    private static function checkIfErrorCanAlsoBeStringSample(): array
    {
        $data = [
            'success' => false,
            'error' => 'error',
            'code' => 10
        ];

        return [$data, self::getBasicSchema(), true];
    }

    /**
     * @return array
     * @throws InvalidSchemaException
     */
    private static function checkIfCodeCanBeOmittedSample(): array
    {
        $data = [
            'success' => false,
            'error' => 'error'
        ];

        return [$data, self::getBasicSchema(), true];
    }

    /**
     * @return array
     * @throws InvalidSchemaException
     */
    private static function checkThatCodeCanNotBeNull(): array
    {
        $data = [
            'success' => false,
            'error' => 'error',
            'code' => null // wrong type
        ];

        return [$data, self::getBasicSchema(), false];
    }

    /**
     * @return DataSchema
     * @throws InvalidSchemaException
     */
    private static function getBasicSchema(): DataSchema
    {
        $schema = [
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'code' => ['type' => JsonRule::INTEGER_TYPE, 'optional' => true]
        ];

        return (new JsonSchema())->setSchema($schema);
    }
}