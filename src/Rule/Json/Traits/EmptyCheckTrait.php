<?php

namespace hunomina\Validator\Json\Rule\Json\Traits;

trait EmptyCheckTrait
{
    protected bool $empty = false;

    /**
     * @return bool
     */
    public function canBeEmpty(): bool
    {
        return $this->empty;
    }

    /**
     * @param bool $empty
     * @return EmptyCheckTrait
     */
    public function setEmpty(bool $empty): EmptyCheckTrait
    {
        $this->empty = $empty;
        return $this;
    }

    /**
     * @param $data
     * @return bool
     * Return true if the value is "empty" (string, list) and can
     * Or if the value is not empty
     */
    abstract public function validateEmptyness($data): bool;
}