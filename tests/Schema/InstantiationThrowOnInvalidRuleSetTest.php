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
     * @throws InvalidSchemaException
     * @dataProvider getInvalidRuleSets
     */
    public function testThrowOnInvalidRuleSet(array $schema): void
    {
        $this->expectException(InvalidSchemaException::class);

        (new JsonSchema())->setSchema($schema);
    }

    /**
     * @return array
     */
    public function getInvalidLengthRule(): array
    {
        return ['age' => ['type' => JsonRule::INTEGER_TYPE, 'length' => 2]];
    }

    /**
     * @return array
     */
    public function getInvalidPatternRule(): array
    {
        return ['age' => ['type' => JsonRule::INTEGER_TYPE, 'pattern' => '/[0-9]+/']];
    }

    /**
     * @return array
     */
    public function getInvalidMinRule(): array
    {
        return ['name' => ['type' => JsonRule::STRING_TYPE, 'min' => 'c']];
    }

    /**
     * @return array
     */
    public function getInvalidMaxRule(): array
    {
        return ['name' => ['type' => JsonRule::STRING_TYPE, 'max' => 'w']];
    }

    /**
     * @return array
     */
    public function getInvalidEnumRule(): array
    {
        return ['success' => ['type' => JsonRule::BOOLEAN_TYPE, 'enum' => [true, false]]];
    }

    /**
     * @return array
     */
    public function getInvalidDateFormatRule(): array
    {
        return ['data' => ['type' => JsonRule::STRING_TYPE, 'date-format' => 'Ymd']];
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