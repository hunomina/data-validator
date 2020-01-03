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
     */
    public function setNullable(bool $nullable): void
    {
        $this->nullable = $nullable;
    }
}