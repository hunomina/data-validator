<?php

namespace hunomina\Validator\Json\Rule\Json\Traits;

trait MinimumCheckTrait
{
    protected ?float $minimum = null;

    /**
     * @return float|null
     */
    public function getMinimum(): ?float
    {
        return $this->minimum;
    }

    /**
     * @param float|null $minimum
     * @return MinimumCheckTrait
     */
    public function setMinimum(?float $minimum): MinimumCheckTrait
    {
        $this->minimum = $minimum;
        return $this;
    }

    /**
     * @param $data
     * @return bool
     * Return true if the value is greater or equals to $this->minimum
     * For integer check => Cast the parameter
     */
    abstract public function validateMinimum($data): bool;
}