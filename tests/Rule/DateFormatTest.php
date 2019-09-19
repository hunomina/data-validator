<?php

namespace hunomina\Validator\Json\Test\Rule;

use hunomina\Validator\Json\Data\JsonData;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use PHPUnit\Framework\TestCase;

class DateFormatTest extends TestCase
{
    /**
     * @throws InvalidSchemaException
     */
    public function testYmd(): void
    {
        $data = (new JsonData())->setDataFromArray([
            'birthday' => '2000-01-01'
        ]);

        $data2 = (new JsonData())->setDataFromArray([
            'birthday' => 'January the first in 2000'
        ]);

        $schema = (new JsonSchema())->setSchema([
            'birthday' => ['type' => JsonRule::STRING_TYPE, 'date-format' => 'Y-m-d']
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testTimestamp(): void
    {
        $data = (new JsonData())->setDataFromArray([
            'birthday' => '1234567890'
        ]);

        $data2 = (new JsonData())->setDataFromArray([
            'birthday' => '2000-01-01'
        ]);

        $schema = (new JsonSchema())->setSchema([
            'birthday' => ['type' => JsonRule::STRING_TYPE, 'date-format' => 'U']
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     * @see https://www.w3.org/TR/NOTE-datetime
     */
    public function testAdvancedFormat(): void
    {
        $data = (new JsonData())->setDataFromArray([
            'birthday' => 'Sat 01 January 2000'
        ]);

        $data2 = (new JsonData())->setDataFromArray([
            'birthday' => '2000-01-01'
        ]);

        $schema = (new JsonSchema())->setSchema([
            'birthday' => ['type' => JsonRule::STRING_TYPE, 'date-format' => 'D d F Y']
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }

    /**
     * @throws InvalidSchemaException
     */
    public function testExistingDate(): void
    {
        $data = (new JsonData())->setDataFromArray([
            'birthday' => 'Sat 01 January 2000' // valid day
        ]);

        $data2 = (new JsonData())->setDataFromArray([
            'birthday' => 'Mon 01 January 2000' // invalid day
        ]);

        $schema = (new JsonSchema())->setSchema([
            'birthday' => ['type' => JsonRule::STRING_TYPE, 'date-format' => 'D d F Y']
        ]);

        $this->assertTrue($schema->validate($data));
        $this->assertFalse($schema->validate($data2));
    }
}