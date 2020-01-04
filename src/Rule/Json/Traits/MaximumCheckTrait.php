<?php

namespace hunomina\DataValidator\Rule\Json\Traits;

trait MaximumCheckTrait
{
    protected ?float $maximum = null;

    /**
     * @return float|null
     * @codeCoverageIgnore
     */
    public function getMaximum(): ?float
    {
        return $this->maximum;
    }

    /**
     * @param float|null $maximum
     */
    public function setMaximum(?float $maximum): void
    {
        $this->maximum = $maximum;
    }

    /**
     * @param $data
     * @return bool
     * Return true if the value is lower or equals to $this->maximum
     * For integer check => Cast the parameter
     */
    abstract public function validateMaximum($data): bool;
}