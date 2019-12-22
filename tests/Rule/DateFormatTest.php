<?php

namespace hunomina\Validator\Json\Test\Rule;

use hunomina\Validator\Json\Data\JsonData;
use hunomina\Validator\Json\Exception\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use PHPUnit\Framework\TestCase;

class DateFormatTest extends TestCase
{
    /**
     * @throws InvalidSchemaException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    public function testYmd(): void
    {
        $data = new JsonData([
            'birthday' => '2000-01-01'
        ]);

        $data2 = new JsonData([
            'birthday' => 'January the first in 2000'
        ]);

        $schema = new JsonSchema([
            'birthday' => ['type' => JsonRule::STRING_TYPE, 'date-format' => 'Y-m-d']
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    public function testTimestamp(): void
    {
        $data = new JsonData([
            'birthday' => '1234567890'
        ]);

        $data2 = new JsonData([
            'birthday' => '2000-01-01'
        ]);

        $schema = new JsonSchema([
            'birthday' => ['type' => JsonRule::STRING_TYPE, 'date-format' => 'U']
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @see https://www.w3.org/TR/NOTE-datetime
     */
    public function testAdvancedFormat(): void
    {
        $data = new JsonData([
            'birthday' => 'Sat 01 January 2000'
        ]);

        $data2 = new JsonData([
            'birthday' => '2000-01-01'
        ]);

        $schema = new JsonSchema([
            'birthday' => ['type' => JsonRule::STRING_TYPE, 'date-format' => 'D d F Y']
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    public function testExistingDate(): void
    {
        $data = new JsonData([
            'birthday' => 'Sat 01 January 2000' // valid day
        ]);

        $data2 = new JsonData([
            'birthday' => 'Mon 01 January 2000' // invalid day
        ]);

        $schema = new JsonSchema([
            'birthday' => ['type' => JsonRule::STRING_TYPE, 'date-format' => 'D d F Y']
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }
}