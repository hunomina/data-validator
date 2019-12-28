<?php

namespace hunomina\Validator\Json\Schema;

use hunomina\Validator\Json\Data\DataType;

interface DataSchema
{
    /**
     * @param DataType $data
     * @return mixed
     * Check if the data validates the schema
     */
    public function validate(DataType $data): bool;

    /**
     * @param array $schema
     * @return $this
     */
    public function setSchema(array $schema): self;
}