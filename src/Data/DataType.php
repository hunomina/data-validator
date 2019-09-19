<?php

namespace hunomina\Validator\Json\Data;

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
     * Process to transform data into an actual data type
     */
    public function format($data);
}