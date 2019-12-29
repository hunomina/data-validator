<?php

namespace hunomina\Validator\Json\Data;

use ArrayAccess;
use hunomina\Validator\Json\Exception\InvalidDataException;
use JsonException;

class JsonData implements DataType, ArrayAccess
{
    /**
     * @var array $data
     */
    private ?array $data = null;

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
     * @return array
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
     * Whether a offset exists
     * @link https://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     * @codeCoverageIgnore
     */
    public function offsetExists($offset): bool
    {
        return isset($this->data[$offset]);
    }

    /**
     * Offset to retrieve
     * @link https://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     * @codeCoverageIgnore
     */
    public function offsetGet($offset)
    {
        if ($this->offsetExists($offset)) {
            return $this->data[$offset];
        }
        return null;
    }

    /**
     * Offset to set
     * @link https://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     * @codeCoverageIgnore
     */
    public function offsetSet($offset, $value): void
    {
        $this->data[$offset] = $value;
    }

    /**
     * Offset to unset
     * @link https://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     * @codeCoverageIgnore
     */
    public function offsetUnset($offset): void
    {
        unset($this->data[$offset]);
    }
}