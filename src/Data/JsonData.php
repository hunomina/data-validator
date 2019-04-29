<?php

namespace hunomina\Validator\Json\Data;

use hunomina\Validator\Json\Exception\InvalidDataException;

class JsonData implements DataType
{
    /**
     * @var array $data
     */
    private $data;

    /**
     * @return array
     */
    public function getData(): ?array
    {
        return $this->data;
    }

    /**
     * @param string $data
     * @return DataType
     * @throws InvalidDataException
     */
    public function setData($data): DataType
    {
        if ($data === null) {
            $this->data = null;
        } else {
            $this->setDataAsArray($this->format($data));
        }
        return $this;
    }

    /**
     * @param array $data
     * @return DataType
     */
    public function setDataAsArray(array $data): DataType
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param $data
     * @return mixed
     * @throws InvalidDataException
     */
    public function format($data): array
    {
        if (!is_string($data)) {
            throw new InvalidDataException('Can only parse string to json data');
        }

        $jsonData = json_decode($data, true);

        if (!is_array($jsonData)) {
            throw new InvalidDataException('Invalid string to parse to json data');
        }

        return $jsonData;
    }
}