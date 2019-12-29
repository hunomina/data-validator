<?php

namespace hunomina\Validator\Json\Test\Rule\Type;

use hunomina\Validator\Json\Data\JsonData;
use hunomina\Validator\Json\Exception\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use PHPUnit\Framework\TestCase;
use Throwable;

class TypedListTypeTest extends TestCase
{
    /**
     * @dataProvider getTestableData
     * @param JsonData $data
     * @param JsonSchema $schema
     * @param bool $success
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    public function testTypedListCheck(JsonData $data, JsonSchema $schema, bool $success): void
    {
        if (!$success) {
            try {
                $schema->validate($data);
            } catch (Throwable $t) {
                // exception thrown by the schema
                $this->assertInstanceOf(InvalidDataException::class, $t);
                $this->assertEquals(InvalidDataException::INVALID_TYPED_LIST_ELEMENT, $t->getCode());

                // exception thrown by the typed list rule
                $t = $t->getPrevious();
                $this->assertInstanceOf(InvalidDataException::class, $t);
                $this->assertEquals(InvalidDataException::INVALID_TYPED_LIST_ELEMENT, $t->getCode());

                // exception thrown by the invalid list element (scalar type)
                $t = $t->getPrevious();
                $this->assertInstanceOf(InvalidDataException::class, $t);
                $this->assertEquals(InvalidDataException::INVALID_DATA_TYPE, $t->getCode());
            }
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
            $this->IntList(),
            $this->IntListFail(),
            $this->StringList(),
            $this->StringListFail(),
            $this->CharList(),
            $this->CharListFail(),
            $this->BooleanList(),
            $this->BooleanListFail(),
            $this->FloatList(),
            $this->FloatListFail(),
            $this->NumericList(),
            $this->NumericListFail()
        ];
    }

    /**
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    public function IntList(): array
    {
        return [
            new JsonData([
                'integers' => [1, 2, 3, 4]
            ]),
            new JsonSchema([
                'integers' => ['type' => JsonRule::INTEGER_LIST_TYPE]
            ]),
            true
        ];
    }

    /**
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    public function IntListFail(): array
    {
        return [
            new JsonData([
                'integers' => [1, 2.89, 3.14158, 4.0]
            ]),
            new JsonSchema([
                'integers' => ['type' => JsonRule::INTEGER_LIST_TYPE]
            ]),
            false
        ];
    }

    /**
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    public function StringList(): array
    {
        return [
            new JsonData([
                'strings' => ['I', 'am', 'testing']
            ]),
            new JsonSchema([
                'strings' => ['type' => JsonRule::STRING_LIST_TYPE]
            ]),
            true
        ];
    }

    /**
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    public function StringListFail(): array
    {
        return [
            new JsonData([
                'strings' => ['I', 'am', 'testing', 'for', 'the', 2, 'nd', 'time']
            ]),
            new JsonSchema([
                'strings' => ['type' => JsonRule::STRING_LIST_TYPE]
            ]),
            false
        ];
    }

    /**
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    public function CharList(): array
    {
        return [
            new JsonData([
                'characters' => ['a', 'b', 'c', 'd']
            ]),
            new JsonSchema([
                'characters' => ['type' => JsonRule::CHAR_LIST_TYPE]
            ]),
            true
        ];
    }

    /**
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    public function CharListFail(): array
    {
        return [
            new JsonData([
                'characters' => ['a', 'bc', 'd', 'e']
            ]),
            new JsonSchema([
                'characters' => ['type' => JsonRule::CHAR_LIST_TYPE]
            ]),
            false
        ];
    }

    /**
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    public function BooleanList(): array
    {
        return [
            new JsonData([
                'booleans' => [true, false, false, true]
            ]),
            new JsonSchema([
                'booleans' => ['type' => JsonRule::BOOLEAN_LIST_TYPE]
            ]),
            true
        ];
    }

    /**
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    public function BooleanListFail(): array
    {
        return [
            new JsonData([
                'booleans' => [true, false, 0, true]
            ]),
            new JsonSchema([
                'booleans' => ['type' => JsonRule::BOOLEAN_LIST_TYPE]
            ]),
            false
        ];
    }

    /**
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    public function FloatList(): array
    {
        return [
            new JsonData([
                'users' => [1.1, 2.2, 3.3, 4.4]
            ]),
            new JsonSchema([
                'users' => ['type' => JsonRule::FLOAT_LIST_TYPE]
            ]),
            true
        ];
    }

    /**
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    public function FloatListFail(): array
    {
        return [
            new JsonData([
                'users' => [1, 2.2, 3.3, 4.4]
            ]),
            new JsonSchema([
                'users' => ['type' => JsonRule::FLOAT_LIST_TYPE]
            ]),
            false
        ];
    }

    /**
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    public function NumericList(): array
    {
        return [
            new JsonData([
                'users' => [1, 2.89, 3.14158, 4.0]
            ]),
            new JsonSchema([
                'users' => ['type' => JsonRule::NUMERIC_LIST_TYPE]
            ]),
            true
        ];
    }

    /**
     * @throws InvalidDataException
     * @throws InvalidSchemaException
     */
    public function NumericListFail(): array
    {
        return [
            new JsonData([
                'users' => ['a', 2.89, 3.14158, 4.0]
            ]),
            new JsonSchema([
                'users' => ['type' => JsonRule::NUMERIC_LIST_TYPE]
            ]),
            false
        ];
    }
}