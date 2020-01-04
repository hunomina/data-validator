<?php

namespace hunomina\DataValidator\Rule\Json\Traits;

trait NullCheckTrait
{
    protected bool $nullable = false;

    /**
     * @return bool
     * @codeCoverageIgnore
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