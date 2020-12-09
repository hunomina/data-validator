<?php

namespace hunomina\DataValidator\Rule\Json\Check;

trait MinimumCheckTrait
{
    protected ?float $minimum = null;

    /**
     * @return float|null
     * @codeCoverageIgnore
     */
    public function getMinimum(): ?float
    {
        return $this->minimum;
    }

    /**
     * @param float|null $minimum
     */
    public function setMinimum(?float $minimum): void
    {
        $this->minimum = $minimum;
    }

    /**
     * @param $data
     * @return bool
     * Return true if the value is greater or equals to $this->minimum
     * For integer check => Cast the parameter
     */
    abstract public function validateMinimum($data): bool;
}