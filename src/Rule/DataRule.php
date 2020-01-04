<?php

namespace hunomina\DataValidator\Rule;

use hunomina\DataValidator\Exception\Json\InvalidDataException;

interface DataRule
{
    /**
     * @param $data
     * @return bool
     * @throws InvalidDataException when data is invalid
     * Validate a data based on his type and length (if possible)
     */
    public function validate($data): bool;
}