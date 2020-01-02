<?php

namespace hunomina\Validator\Json\Rule\Json\Traits;

trait NullCheckTrait
{
    protected bool $nullable = false;

    /**
     * @return bool
     */
    public function canBeNull(): bool
    {
        return $this->nullable;
    }

    /**
     * @param bool $nullable
     * @return NullCheckTrait
     */
    public function setNullable(bool $nullable): NullCheckTrait
    {
        $this->nullable = $nullable;
        return $this;
    }

    /**
     * @param $data
     * @return bool
     */
    public function validateNullness($data): bool
    {
        if ($data !== null) {
            return true;
        }

        return $this->nullable;
    }
}