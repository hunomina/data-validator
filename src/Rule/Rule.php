<?php

namespace hunomina\Validator\Json\Rule;

use hunomina\Validator\Json\Exception\Json\InvalidDataException;

abstract class Rule
{
    protected bool $optional = false;

    /**
     * @param $data
     * @return bool
     * @throws InvalidDataException when data is invalid
     * Validate a data based on his type and length (if possible)
     */
    abstract public function validate($data): bool;

    /**
     * @return bool
     * Is the data optional <=> is it mandatory in the parent schema
     */
    public function isOptional(): bool
    {
        return $this->optional;
    }

    /**
     * @param bool $optional
     */
    public function setOptional(bool $optional): void
    {
        $this->optional = $optional;
    }
}