<?php

namespace hunomina\Validator\Json\Rule\Json\Traits;

trait EnumCheckTrait
{
    protected ?array $enum = null;

    /**
     * @return array|null
     */
    public function getEnum(): ?array
    {
        return $this->enum;
    }

    /**
     * @param array|null $enum
     * @return EnumCheckTrait
     */
    public function setEnum(?array $enum): EnumCheckTrait
    {
        $this->enum = $enum;
        return $this;
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