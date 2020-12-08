<?php

namespace hunomina\DataValidator\Test\Rule\Json;

use hunomina\DataValidator\Data\Json\JsonData;
use hunomina\DataValidator\Exception\Json\InvalidDataException;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use PHPUnit\Framework\TestCase;

/**
 * Class CharacterRuleTest
 * @package hunomina\DataValidator\Test\Rule\Json
 * @covers \hunomina\DataValidator\Rule\Json\CharacterRule
 */
class CharacterRuleTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param JsonData $data
     * @param JsonSchema $schema
     * @param bool $success
     * @throws InvalidDataException
     */
    public function testCharacterType(JsonData $data, JsonSchema $schema, bool $success): void
    {
        if (!$success) {
            $this->expectException(InvalidDataException::class);
            $this->expectExceptionCode(InvalidDataException::INVALID_DATA_TYPE);

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
            self::ValidCharacterData(),
            self::InvalidCharacterData()
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     */
    private static function ValidCharacterData(): array
    {
        return [
            new JsonData([
                'character' => 'a'
            ]),
            new JsonSchema([
                'character' => ['type' => JsonRule::CHAR_TYPE]
            ]),
            true
        ];
    }

    /**
     * @return array
     * @throws InvalidDataException
     */
    private static function InvalidCharacterData(): array
    {
        return [
            new JsonData([
                'character' => 'not-a-character'
            ]),
            new JsonSchema([
                'character' => ['type' => JsonRule::CHAR_TYPE]
            ]),
            false
        ];
    }
}