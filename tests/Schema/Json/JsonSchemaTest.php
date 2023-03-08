<?php

namespace hunomina\DataValidator\Test\Schema\Json;

use hunomina\DataValidator\Data\Json\JsonData;
use hunomina\DataValidator\Exception\Json\InvalidDataException;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use PHPUnit\Framework\TestCase;
use Throwable;

class JsonSchemaTest extends TestCase
{
    public function testWrapsRuleExceptionOnInvalidData(): void
    {
        $schema = new JsonSchema(['test' => ['type' => JsonRule::CHAR_TYPE]]);

        try {
            $schema->validate(new JsonData(['test' => 'notACharacter']));
        } catch (Throwable $t) {
            self::assertInstanceOf(InvalidDataException::class, $t);
            $previous = $t->getPrevious();
            self::assertInstanceOf(InvalidDataException::class, $previous);
            self::assertStringContainsString($previous->getMessage(), $t->getMessage());
            self::assertSame($previous->getCode(), $t->getCode());
        }
    }
}
