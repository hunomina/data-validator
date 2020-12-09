<?php

namespace hunomina\DataValidator\Rule\Json\Check;

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
     * @codeCoverageIgnore
     */
    public function setNullable(bool $nullable): void
    {
        $this->nullable = $nullable;
    }
}