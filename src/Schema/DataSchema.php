<?php

namespace hunomina\DataValidator\Schema;

use hunomina\DataValidator\Data\DataType;
use hunomina\DataValidator\Rule\DataRule;

interface DataSchema
{
    /**
     * @param DataType $data
     * @return mixed
     * Check if the data validates the schema
     * Should throw InvalidDataTypeArgumentException on invalid $data parameter type
     */
    public function validate(DataType $data): bool;

    /**
     * @param array $schema
     * @return $this
     */
    public function setSchema(array $schema): self;

    /**
     * @return DataRule[]|array
     * Get the schema rules
     */
    public function getRules(): array;

    /**
     * @return DataSchema[]|array
     * Get the children sub schemas
     */
    public function getChildren(): array;
}