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
     * @return null|string
     * When validate fail, an error must be set, not returned or thrown
     * User can access it using this method
     */
    public function getLastError(): ?string;

    public function setSchema(array $schema): self;
}