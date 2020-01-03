<?php

namespace hunomina\Validator\Json\Test\Rule;

use hunomina\Validator\Json\Data\Json\JsonData;
use hunomina\Validator\Json\Exception\Json\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\Json\InvalidSchemaException;
use hunomina\Validator\Json\Rule\Json\JsonRule;
use hunomina\Validator\Json\Schema\Json\JsonSchema;
use PHPUnit\Framework\TestCase;
use Throwable;

class PatternCheckTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param JsonData $data
     * @param JsonSchema $schema
     * @param bool $success
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    public function testPatternCheck(JsonData $data, JsonSchema $schema, bool $success): void
    {
        if (!$success) {
            $this->expectException(InvalidDataException::class);
            $this->expectExceptionCode(InvalidDataException::PATTERN_NOT_MATCHED);

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
            self::PatternStringCheck(),
            self::PatternStringCheckFail(),
            self::PatternCharCheck(),
            self::PatternCharCheckFail(),
            self::PatternStringListCheck(),
            self::PatternCharListCheck()
        ];
    }

    /**
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function PatternStringCheck(): array
    {
        return [
            new JsonData([
                'name' => 'test'
            ]),
            new JsonSchema([
                'name' => ['type' => JsonRule::STRING_TYPE, 'pattern' => '/^[a-z]+$/']
            ]),
            true
        ];
    }

    /**
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function PatternStringCheckFail(): array
    {
        return [
            new JsonData([
                'name' => 'test2'
            ]),
            new JsonSchema([
                'name' => ['type' => JsonRule::STRING_TYPE, 'pattern' => '/^[a-z]+$/']
            ]),
            false
        ];
    }

    /**
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function PatternCharCheck(): array
    {
        return [
            new JsonData([
                'blood_type' => 'O'
            ]),
            new JsonSchema([
                'blood_type' => ['type' => JsonRule::CHAR_TYPE, 'pattern' => '/^[ABO]$/']
            ]),
            true
        ];
    }

    /**
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function PatternCharCheckFail(): array
    {
        return [
            new JsonData([
                'blood_type' => 'C'
            ]),
            new JsonSchema([
                'blood_type' => ['type' => JsonRule::CHAR_TYPE, 'pattern' => '/^[ABO]$/']
            ]),
            false
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function PatternStringListCheck(): array
    {
        return [
            new JsonData([
                'list' => [
                    'hello',
                    'love',
                    'test'
                ]
            ]),
            new JsonSchema([
                'list' => ['type' => JsonRule::STRING_LIST_TYPE, 'pattern' => '/^[a-zA-Z]+$/']
            ]),
            true
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    private static function PatternCharListCheck(): array
    {
        return [
            new JsonData([
                'list' => [
                    'A',
                    'B',
                    'O'
                ]
            ]),
            new JsonSchema([
                'list' => ['type' => JsonRule::CHAR_LIST_TYPE, 'pattern' => '/^[ABO]+$/']
            ]),
            true
        ];
    }

    /**
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    public function testPatternStringListCheckFail(): void
    {
        $data = new JsonData([
            'list' => [
                'won\'t',
                'work',
                'sorry'
            ]
        ]);

        $schema = new JsonSchema([
            'list' => ['type' => JsonRule::STRING_LIST_TYPE, 'pattern' => '/^[a-zA-Z]+$/']
        ]);

        try {
            $schema->validate($data);
        } catch (Throwable $t) {
            $this->assertInstanceOf(InvalidDataException::class, $t);
            $this->assertEquals(InvalidDataException::INVALID_TYPED_LIST_ELEMENT, $t->getCode());

            $t = $t->getPrevious();
            $this->assertInstanceOf(InvalidDataException::class, $t);
            $this->assertEquals(InvalidDataException::INVALID_TYPED_LIST_ELEMENT, $t->getCode());

            $t = $t->getPrevious();
            $this->assertInstanceOf(InvalidDataException::class, $t);
            $this->assertEquals(InvalidDataException::PATTERN_NOT_MATCHED, $t->getCode());
        }
    }

    /**
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    public function testPatternCharListCheckFail(): void
    {
        $data = new JsonData([
            'list' => [
                'A',
                'B',
                'C'
            ]
        ]);

        $schema = new JsonSchema([
            'list' => ['type' => JsonRule::CHAR_LIST_TYPE, 'pattern' => '/^[ABO]+$/']
        ]);

        try {
            $schema->validate($data);
        } catch (Throwable $t) {
            $this->assertInstanceOf(InvalidDataException::class, $t);
            $this->assertEquals(InvalidDataException::INVALID_TYPED_LIST_ELEMENT, $t->getCode());

            $t = $t->getPrevious();
            $this->assertInstanceOf(InvalidDataException::class, $t);
            $this->assertEquals(InvalidDataException::INVALID_TYPED_LIST_ELEMENT, $t->getCode());

            $t = $t->getPrevious();
            $this->assertInstanceOf(InvalidDataException::class, $t);
            $this->assertEquals(InvalidDataException::PATTERN_NOT_MATCHED, $t->getCode());
        }
    }
}