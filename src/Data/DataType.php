<?php

namespace hunomina\DataValidator\Data;

interface DataType
{
    /**
     * @return mixed
     */
    public function getData();

    /**
     * @param $data
     * @return DataType
     */
    public function setData($data): self;

    /**
     * @param $data
     * @return mixed
     * Process to transform raw data into operable data
     */
    public function format($data);
}