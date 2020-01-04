<?php

namespace hunomina\Validator\Json\Rule;

use hunomina\Validator\Json\Exception\Json\InvalidDataException;

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