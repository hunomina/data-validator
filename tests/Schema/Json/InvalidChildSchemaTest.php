<?php

namespace hunomina\DataValidator\Test\Schema\Json;

use hunomina\DataValidator\Exception\Json\InvalidSchemaException;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use PHPUnit\Framework\TestCase;
use Throwable;

class InvalidChildSchemaTest extends TestCase
{
    public function testThrowOnNonArrayChildSchema(): void
    {
        $this->expectException(InvalidSchemaException::class);
        $this->expectExceptionCode(InvalidSchemaException::INVALID_CHILD_SCHEMA);

        new JsonSchema([
            'object' => ['type' => JsonRule::OBJECT_TYPE, 'schema' => 'invalid-schema'] // not an array
        ]);
    }

    public function testThrowOnInvalidChildSchema(): void
    {
        try {
            new JsonSchema([
                'object' => ['type' => JsonRule::OBJECT_TYPE, 'schema' => ['field']] // invalid schema: missing field type
            ]);
        } catch (Throwable $t) {
            $this->assertInstanceOf(InvalidSchemaException::class, $t);
            $this->assertEquals(InvalidSchemaException::INVALID_CHILD_SCHEMA, $t->getCode());

            $t = $t->getPrevious();
            $this->assertInstanceOf(InvalidSchemaException::class, $t);
            $this->assertEquals(InvalidSchemaException::MISSING_TYPE, $t->getCode());
        }
    }
}