<?php

namespace hunomina\DataValidator\Test\Schema\Json;

use hunomina\DataValidator\Data\Json\JsonData;
use hunomina\DataValidator\Exception\Json\InvalidDataException;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use PHPUnit\Framework\TestCase;
use Throwable;

/**
 * Class MandatoryFieldTest
 * @package hunomina\DataValidator\Test\Schema\Json
 * @covers \hunomina\DataValidator\Schema\Json\JsonSchema::validateObject
 */
class MandatoryFieldTest extends TestCase
{
    /**
     * @throws InvalidDataException
     */
    public function testThrowOnMissingScalarMandatoryField(): void
    {
        $schema = new JsonSchema([
            'test' => ['type' => JsonRule::STRING_TYPE]
        ]);
        $data = new JsonData([]);

        try {
            $schema->validate($data);
        } catch (Throwable $t) {
            self::assertInstanceOf(InvalidDataException::class, $t);
            self::assertSame(InvalidDataException::MANDATORY_FIELD, $t->getCode());
        }
    }

    /**
     * @throws InvalidDataException
     */
    public function testThrowOnMissingChildMandatoryField(): void
    {
        $schema = new JsonSchema([
            'test' => ['type' => JsonRule::OBJECT_TYPE, 'schema' => [
                'childProperty' => ['type' => JsonRule::INTEGER_TYPE]
            ]]
        ]);
        $data = new JsonData([]);

        try {
            $schema->validate($data);
        } catch (Throwable $t) {
            self::assertInstanceOf(InvalidDataException::class, $t);
            self::assertSame(InvalidDataException::MANDATORY_FIELD, $t->getCode());
        }
    }
}