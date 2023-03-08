<?php

namespace hunomina\DataValidator\Data\Json;

use ArrayAccess;
use hunomina\DataValidator\Data\DataType;
use hunomina\DataValidator\Exception\Json\InvalidDataException;
use JsonException;

class JsonData implements DataType, ArrayAccess
{
    private ?array $data;

    /**
     * JsonData constructor.
     * @param null $data
     * @throws InvalidDataException
     */
    public function __construct($data = null)
    {
        $this->setData($data);
    }

    /**
     * @return array|null
     * @codeCoverageIgnore
     */
    public function getData(): ?array
    {
        return $this->data;
    }

    /**
     * @param $data
     * @return DataType
     * @throws InvalidDataException
     */
    public function setData($data): DataType
    {
        if ($data === null) {
            $this->data = null;
        } else if (is_string($data)) {
            $this->data = $this->format($data);
        } else if (is_array($data)) {
            $this->data = $data;
        } else {
            throw new InvalidDataException('Invalid data type', InvalidDataException::INVALID_DATA_TYPE);
        }

        return $this;
    }

    /**
     * @param $data
     * @return array
     * @throws InvalidDataException
     */
    public function format($data): array
    {
        if (!is_string($data)) {
            throw new InvalidDataException('Can only parse string to json data', InvalidDataException::INVALID_DATA_TYPE);
        }

        try {
            $jsonData = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new InvalidDataException('Invalid string to parse to json data : ' . $e->getMessage(), InvalidDataException::INVALID_JSON_DATA);
        }

        if (!is_array($jsonData)) {
            throw new InvalidDataException('Invalid string to parse to json data', InvalidDataException::INVALID_JSON_DATA);
        }

        return $jsonData;
    }

    /**
     * @param mixed $offset
     * @return bool
     * @codeCoverageIgnore
     */
    public function offsetExists($offset): bool
    {
        return isset($this->data[$offset]);
    }

    /**
     * @param mixed $offset
     * @return mixed|null
     * @codeCoverageIgnore
     */
    public function offsetGet($offset): mixed
    {
        if ($this->offsetExists($offset)) {
            return $this->data[$offset];
        }
        return null;
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @codeCoverageIgnore
     */
    public function offsetSet($offset, $value): void
    {
        $this->data[$offset] = $value;
    }

    /**
     * @param mixed $offset
     * @codeCoverageIgnore
     */
    public function offsetUnset($offset): void
    {
        unset($this->data[$offset]);
    }
}