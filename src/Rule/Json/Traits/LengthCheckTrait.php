<?php

namespace hunomina\Validator\Json\Rule\Json\Traits;

trait LengthCheckTrait
{
    protected ?int $length = null;

    /**
     * @return int|null
     */
    public function getLength(): ?int
    {
        return $this->length;
    }

    /**
     * @param int|null $length
     */
    public function setLength(?int $length): void
    {
        $this->length = $length;
    }

    /**
     * @param $data
     * @return bool
     * Return true if the data length (regardless of the type, could be a string, an array, ...) equals $this->length
     */
    abstract public function validateLength($data): bool;
}