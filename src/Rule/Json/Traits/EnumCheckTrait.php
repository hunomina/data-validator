<?php

namespace hunomina\Validator\Json\Rule\Json\Traits;

use hunomina\Validator\Json\Exception\Json\InvalidRuleException;

trait EnumCheckTrait
{
    protected ?array $enum = null;

    /**
     * @return array|null
     * @codeCoverageIgnore
     */
    public function getEnum(): ?array
    {
        return $this->enum;
    }

    /**
     * @param array|null $enum
     * @throws InvalidRuleException
     */
    public function setEnum(?array $enum): void
    {
        if ($enum !== null && count($enum) === 0) {
            throw new InvalidRuleException('`enum` option can not be an empty array', InvalidRuleException::INVALID_ENUM_RULE);
        }

        $this->enum = $enum;
    }

    /**
     * @param $data
     * @return bool
     * Return true if the data can be found in $this->enum
     */
    public function validateEnum($data): bool
    {
        if ($this->enum === null) {
            return true;
        }

        return in_array($data, $this->enum, true);
    }
}