<?php

namespace hunomina\Validator\Json\Test\Schema;

use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use PHPUnit\Framework\TestCase;

class InstantiationThrowOnInvalidRuleSetTest extends TestCase
{
    /**
     * @param array $schema
     * @param int $exceptionCode
     * @throws InvalidSchemaException
     * @dataProvider getInvalidRuleSets
     */
    public function testThrowOnInvalidRuleSet(array $schema, int $exceptionCode): void
    {
        $this->expectException(InvalidSchemaException::class);
        $this->expectExceptionCode($exceptionCode);

        (new JsonSchema())->setSchema($schema);
    }

    /**
     * @return array
     */
    public function getInvalidLengthRule(): array
    {
        return [['age' => ['type' => JsonRule::INTEGER_TYPE, 'length' => 2]], InvalidSchemaException::INVALID_LENGTH_RULE];
    }

    /**
     * @return array
     */
    public function getInvalidPatternRule(): array
    {
        return [['age' => ['type' => JsonRule::INTEGER_TYPE, 'pattern' => '/[0-9]+/']], InvalidSchemaException::INVALID_PATTERN_RULE];
    }

    /**
     * @return array
     */
    public function getInvalidMinRule(): array
    {
        return [['name' => ['type' => JsonRule::STRING_TYPE, 'min' => 'c']], InvalidSchemaException::INVALID_MIN_RULE];
    }

    /**
     * @return array
     */
    public function getInvalidMaxRule(): array
    {
        return [['name' => ['type' => JsonRule::STRING_TYPE, 'max' => 'w']], InvalidSchemaException::INVALID_MAX_RULE];
    }

    /**
     * @return array
     */
    public function getInvalidEnumRule(): array
    {
        return [['success' => ['type' => JsonRule::BOOLEAN_TYPE, 'enum' => [true, false]]], InvalidSchemaException::INVALID_ENUM_RULE];
    }

    /**
     * @return array
     */
    public function getInvalidDateFormatRule(): array
    {
        return [['date' => ['type' => JsonRule::INTEGER_TYPE, 'date-format' => 'Ymd']], InvalidSchemaException::INVALID_DATE_FORMAT_RULE];
    }

    /**
     * @return array
     */
    public function getInvalidRuleSets(): array
    {
        return [
            $this->getInvalidLengthRule(),
            $this->getInvalidPatternRule(),
            $this->getInvalidMinRule(),
            $this->getInvalidMaxRule(),
            $this->getInvalidEnumRule(),
            $this->getInvalidDateFormatRule(),
        ];
    }
}