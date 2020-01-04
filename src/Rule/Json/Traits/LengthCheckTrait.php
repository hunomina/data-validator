<?php

namespace hunomina\DataValidator\Rule\Json\Traits;

use hunomina\DataValidator\Exception\Json\InvalidRuleException;

trait LengthCheckTrait
{
    protected ?int $length = null;

    /**
     * @return int|null
     * @codeCoverageIgnore
     */
    public function getLength(): ?int
    {
        return $this->length;
    }

    /**
     * @param int|null $length
     * @throws InvalidRuleException
     */
    public function setLength(?int $length): void
    {
        if ($length !== null && $length < 1) {
            throw new InvalidRuleException('`length` option must be greater or equal to 1', InvalidRuleException::INVALID_LENGTH_RULE);
        }

        $this->length = $length;
    }

    /**
     * @param $data
     * @return bool
     * Return true if the data length (regardless of the type, could be a string, an array, ...) equals $this->length
     */
    abstract public function validateLength($data): bool;
}