<?php

namespace hunomina\DataValidator\Rule\Json\Check;

trait EmptyCheckTrait
{
    protected bool $empty = false;

    /**
     * @return bool
     * @codeCoverageIgnore
     */
    public function canBeEmpty(): bool
    {
        return $this->empty;
    }

    /**
     * @param bool $empty
     */
    public function setEmpty(bool $empty): void
    {
        $this->empty = $empty;
    }

    /**
     * @param $data
     * @return bool
     * Return true if the value is "empty" (string, list) and can
     * Or if the value is not empty
     */
    abstract public function validateEmptiness($data): bool;
}