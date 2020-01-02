<?php

namespace hunomina\Validator\Json\Rule\Json\Traits;

trait MaximumCheckTrait
{
    protected ?float $maximum = null;

    /**
     * @return float|null
     */
    public function getMaximum(): ?float
    {
        return $this->maximum;
    }

    /**
     * @param float|null $maximum
     * @return MaximumCheckTrait
     */
    public function setMaximum(?float $maximum): MaximumCheckTrait
    {
        $this->maximum = $maximum;
        return $this;
    }

    /**
     * @param $data
     * @return bool
     * Return true if the value is lower or equals to $this->maximum
     * For integer check => Cast the parameter
     */
    abstract public function validateMaximum($data): bool;
}