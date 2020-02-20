<?php

namespace hunomina\DataValidator\Test\Data;

use hunomina\DataValidator\Data\DataType;

class TestDataType implements DataType
{
    public function getData()
    {
    }

    public function setData($data): DataType
    {
        return $this;
    }

    public function format($data)
    {
    }
}