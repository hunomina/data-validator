<?php

namespace hunomina\Validator\Json\Test;

use hunomina\Validator\Json\Data\JsonData;
use hunomina\Validator\Json\Exception\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Schema\JsonSchema;
use PHPUnit\Framework\TestCase;

class ThirdLevelJsonValidatorTest extends TestCase
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
            self::checkWithOnlyOneBirthDate(),
            self::checkWithOneNullBirthDate(),
            self::checkWithOptionalTime(),
            self::checkWithOnlyOneGender(),
            self::checkWithOnlyOneUser(),
            self::checkWithoutUser(),
            self::checkWithNullUserList(),
            self::checkWithOptionalUserList()
        ];
    }

    /**
     * @return array
     * @throws InvalidSchemaException
     */
    private static function checkPerfectData(): array
    {
        $data = [
            'success' => true,
            'error' => null,
            'users' => [
                [
                    'name' => 'test',
                    'gender' => 'male',
                    'birth_date' => [
                        'date' => 'THE date',
                        'time' => 'THE time'
                    ]
                ],
                [
                    'name' => 'test2',
                    'gender' => 'female',
                    'birth_date' => [
                        'date' => 'THE second date',
                        'time' => 'THE second time'
                    ]
                ]
            ]
        ];

        return [$data, self::getThirdLevelSchema(), true];
    }

    /**
     * @return array
     * @throws InvalidSchemaException
     */
    private static function checkWithOnlyOneBirthDate(): array
    {
        $data = [
            'success' => true,
            'error' => null,
            'users' => [
                [
                    'name' => 'test',
                    'gender' => 'male',
                    'birth_date' => [
                        'date' => 'THE date',
                        'time' => 'THE time'
                    ]
                ],
                [
                    'name' => 'test2',
                    'gender' => 'female'
                ]
            ]
        ];

        return [$data, self::getThirdLevelSchema(), false];
    }

    /**
     * @return array
     * @throws InvalidSchemaException
     */
    private static function checkWithOneNullBirthDate(): array
    {
        $data = [
            'success' => true,
            'error' => null,
            'users' => [
                [
                    'name' => 'test',
                    'gender' => 'male',
                    'birth_date' => [
                        'date' => 'THE date',
                        'time' => 'THE time'
                    ]
                ],
                [
                    'name' => 'test2',
                    'gender' => 'female',
                    'birth_date' => null
                ]
            ]
        ];

        return [$data, self::getThirdLevelSchema(), true];
    }

    /**
     * @return array
     * @throws InvalidSchemaException
     */
    private static function checkWithOptionalTime(): array
    {
        $data = [
            'success' => true,
            'error' => null,
            'users' => [
                [
                    'name' => 'test',
                    'gender' => 'male',
                    'birth_date' => [
                        'date' => 'THE date'
                    ]
                ],
                [
                    'name' => 'test2',
                    'gender' => 'female',
                    'birth_date' => [
                        'date' => 'THE second date'
                    ]
                ]
            ]
        ];

        return [$data, self::getThirdLevelSchema(), true];
    }

    /**
     * @return array
     * @throws InvalidSchemaException
     */
    private static function checkWithOnlyOneGender(): array
    {
        $data = [
            'success' => true,
            'error' => null,
            'users' => [
                [
                    'name' => 'test',
                    'gender' => 'male',
                    'birth_date' => [
                        'date' => 'THE date',
                        'time' => 'THE time'
                    ]
                ],
                [
                    'name' => 'test2',
                    'birth_date' => [
                        'date' => 'THE second date',
                        'time' => 'THE second time'
                    ]
                ]
            ]
        ];

        return [$data, self::getThirdLevelSchema(), true];
    }

    /**
     * @return array
     * @throws InvalidSchemaException
     */
    private static function checkWithOnlyOneUser(): array
    {
        $data = [
            'success' => true,
            'error' => null,
            'users' => [
                [
                    'name' => 'test',
                    'gender' => 'male',
                    'birth_date' => [
                        'date' => 'THE date',
                        'time' => 'THE time'
                    ]
                ]
            ]
        ];

        return [$data, self::getThirdLevelSchema(), true];
    }

    /**
     * @return array
     * @throws InvalidSchemaException
     */
    private static function checkWithoutUser(): array
    {
        $data = [
            'success' => true,
            'error' => null,
            'users' => []
        ];

        return [$data, self::getThirdLevelSchema(), true];
    }

    /**
     * @return array
     * @throws InvalidSchemaException
     */
    private static function checkWithNullUserList(): array
    {
        $data = [
            'success' => true,
            'error' => null,
            'users' => null
        ];

        return [$data, self::getThirdLevelSchema(), false];
    }

    /**
     * @return array
     * @throws InvalidSchemaException
     */
    private static function checkWithOptionalUserList(): array
    {
        $data = [
            'success' => true,
            'error' => null
        ];

        return [$data, self::getThirdLevelSchema(), true];
    }

    /**
     * @return JsonSchema
     * @throws InvalidSchemaException
     */
    private static function getThirdLevelSchema(): JsonSchema
    {
        $schema = [
            'success' => ['type' => 'bool'],
            'error' => ['type' => 'string', 'null' => true],
            'users' => ['type' => 'list', 'optional' => true, 'schema' => [
                'name' => ['type' => 'string'],
                'gender' => ['type' => 'string', 'optional' => true],
                'birth_date' => ['type' => 'object', 'null' => true, 'schema' => [
                    'date' => ['type' => 'string'],
                    'time' => ['type' => 'string', 'optional' => true],
                ]]
            ]]
        ];

        return (new JsonSchema())->setSchema($schema);
    }
}