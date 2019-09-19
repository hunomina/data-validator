<?php

namespace hunomina\Validator\Json\Test\Schema;

use hunomina\Validator\Json\Data\JsonData;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use PHPUnit\Framework\TestCase;

class ThirdLevelJsonValidatorTest extends TestCase
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
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'users' => ['type' => JsonRule::LIST_TYPE, 'optional' => true, 'schema' => [
                'name' => ['type' => JsonRule::STRING_TYPE],
                'gender' => ['type' => JsonRule::STRING_TYPE, 'optional' => true],
                'birth_date' => ['type' => JsonRule::OBJECT_TYPE, 'null' => true, 'schema' => [
                    'date' => ['type' => JsonRule::STRING_TYPE],
                    'time' => ['type' => JsonRule::STRING_TYPE, 'optional' => true],
                ]]
            ]]
        ];

        $s = new JsonSchema();
        $s->setSchema($schema);

        return $s;
    }
}