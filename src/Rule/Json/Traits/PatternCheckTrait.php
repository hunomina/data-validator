<?php

namespace hunomina\DataValidator\Rule\Json\Traits;

trait PatternCheckTrait
{
    protected ?string $pattern = null;

    /**
     * @return string|null
     * @codeCoverageIgnore
     */
    public function getPattern(): ?string
    {
        return $this->pattern;
    }

    /**
     * @param string|null $pattern
     */
    public function setPattern(?string $pattern): void
    {
        $this->pattern = $pattern;
    }

    /**
     * @param string $data
     * @return bool
     * Return true if the string data match the pattern
     * Regular expressions only apply to string that's why this method is not abstract
     */
    public function validatePattern(string $data): bool
    {
        if ($this->pattern === null) {
            return true;
        }

        return (bool)preg_match($this->pattern, $data);
    }
}